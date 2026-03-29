const ICONS = [
  "apple",
  "apricot",
  "banana",
  "big_win",
  "cherry",
  "grapes",
  "lemon",
  "lucky_seven",
  "orange",
  "pear",
  "strawberry",
  "watermelon",
];

const ICON_FALLBACKS = {
  apple: "🍎",
  apricot: "🍑",
  banana: "🍌",
  big_win: "⭐",
  cherry: "🍒",
  grapes: "🍇",
  lemon: "🍋",
  lucky_seven: "💸",
  orange: "🍊",
  pear: "🍐",
  strawberry: "🍓",
  watermelon: "🍉",
};

/**
 * @type {number} The minimum spin time in seconds
 */
const BASE_SPINNING_DURATION = 2.7;

/**
 * @type {number} The additional duration to the base duration for each row (in seconds).
 * It makes the typical effect that the first reel ends, then the second, and so on...
 */
const COLUMN_SPINNING_DURATION = 0.3;

const STARTING_BALANCE = 1000;
const SPIN_COST = 50;
const JACKPOT_PAYOUT = 777;
const FIVE_OF_A_KIND_PAYOUT = 300;
const FOUR_OF_A_KIND_PAYOUT = 160;
const THREE_OF_A_KIND_PAYOUT = 90;
const LOSS_THRESHOLD_FOR_BONUS = 200;
const LOSS_RECOVERY_PAYOUT = 225;

var cols;
var balance = STARTING_BALANCE;
var isSpinning = false;
var balanceValueEl;
var payoutValueEl;
var paylineValueEl;
var statusValueEl;
var accumulatedLoss = 0;

window.addEventListener("DOMContentLoaded", function (event) {
  cols = document.querySelectorAll(".col");
  balanceValueEl = document.getElementById("balanceValue");
  payoutValueEl = document.getElementById("lastPayout");
  paylineValueEl = document.getElementById("paylineValue");
  statusValueEl = document.getElementById("statusValue");

  setInitialItems();
  renderHud(0, ["-", "-", "-", "-", "-"]);
  setStatus("Press Spin to play.", "neutral");
});

function formatMoney(amount) {
  return "$" + amount;
}

function getDisplayLabel(iconName) {
  return ICON_FALLBACKS[iconName] || iconName;
}

function renderHud(lastPayout, payline) {
  if (balanceValueEl) {
    balanceValueEl.textContent = formatMoney(balance);
  }

  if (payoutValueEl) {
    payoutValueEl.textContent = formatMoney(lastPayout);
  }

  if (paylineValueEl) {
    paylineValueEl.textContent = payline.map(getDisplayLabel).join(" ");
  }
}

function setStatus(message, type) {
  if (!statusValueEl) return;

  statusValueEl.textContent = message;
  statusValueEl.classList.remove("status-win", "status-lose", "status-neutral");
  statusValueEl.classList.add("status-" + type);
}

function countOfAKind(payline) {
  let counts = {};

  for (let icon of payline) {
    counts[icon] = (counts[icon] || 0) + 1;
  }

  let maxCount = 0;
  for (let key in counts) {
    if (counts[key] > maxCount) {
      maxCount = counts[key];
    }
  }

  return maxCount;
}

function evaluatePayout(payline) {
  let isJackpot =
    payline[0] === "lucky_seven" &&
    payline[1] === "lucky_seven" &&
    payline[2] === "lucky_seven";

  if (isJackpot) {
    return {
      payout: JACKPOT_PAYOUT,
      message: "777 JACKPOT!",
      type: "win",
    };
  }

  let sameCount = countOfAKind(payline);
  if (sameCount === 5) {
    return {
      payout: FIVE_OF_A_KIND_PAYOUT,
      message: "5 of a kind!",
      type: "win",
    };
  }

  if (sameCount === 4) {
    return {
      payout: FOUR_OF_A_KIND_PAYOUT,
      message: "4 of a kind!",
      type: "win",
    };
  }

  if (sameCount === 3) {
    return {
      payout: THREE_OF_A_KIND_PAYOUT,
      message: "3 of a kind!",
      type: "win",
    };
  }

  return {
    payout: 0,
    message: "No match. You lose this spin.",
    type: "lose",
  };
}

function applyLossRecoveryRule(outcome) {
  if (outcome.payout > 0) {
    return outcome;
  }

  accumulatedLoss += SPIN_COST;

  if (accumulatedLoss >= LOSS_THRESHOLD_FOR_BONUS) {
    accumulatedLoss = 0;

    return {
      payout: LOSS_RECOVERY_PAYOUT,
      message: "Loss shield activated! You win $225 after $200 losses.",
      type: "win",
    };
  }

  return outcome;
}

function createIconItem(icon) {
  return (
    '<div class="icon" data-item="' +
    icon +
    '"><img src="items/' +
    icon +
    '.png" alt="' +
    icon +
    '" onerror="setFallbackIcon(this)"></div>'
  );
}

function clearFallbackIcon(iconContainer) {
  iconContainer.classList.remove("is-fallback");
  let fallbackLabel = iconContainer.querySelector(".fallback-label");
  if (fallbackLabel) {
    fallbackLabel.remove();
  }
}

function setFallbackIcon(imgOrEvent) {
  let img =
    imgOrEvent instanceof HTMLImageElement
      ? imgOrEvent
      : imgOrEvent && imgOrEvent.currentTarget instanceof HTMLImageElement
        ? imgOrEvent.currentTarget
        : null;

  if (!img) return;

  let iconContainer = img.closest(".icon");
  if (!iconContainer) return;

  let iconName = iconContainer.getAttribute("data-item") || "";
  let fallbackText = ICON_FALLBACKS[iconName] || "?";

  clearFallbackIcon(iconContainer);
  iconContainer.classList.add("is-fallback");

  let fallbackLabel = document.createElement("span");
  fallbackLabel.className = "fallback-label";
  fallbackLabel.textContent = fallbackText;
  iconContainer.appendChild(fallbackLabel);

  img.style.display = "none";
  img.onerror = null;
}

window.setFallbackIcon = setFallbackIcon;

function updateIconImage(img, iconName) {
  let iconContainer = img.closest(".icon");
  if (iconContainer) {
    iconContainer.setAttribute("data-item", iconName);
    clearFallbackIcon(iconContainer);
  }

  img.style.display = "block";
  img.onerror = function () {
    setFallbackIcon(img);
  };
  img.setAttribute("alt", iconName);
  img.setAttribute("src", "items/" + iconName + ".png");
}

function setInitialItems() {
  let baseItemAmount = 40;

  for (let i = 0; i < cols.length; ++i) {
    let col = cols[i];
    let amountOfItems = baseItemAmount + i * 3; // Increment the amount for each column
    let elms = "";
    let firstThreeElms = "";

    for (let x = 0; x < amountOfItems; x++) {
      let icon = getRandomIcon();
      let item = createIconItem(icon);
      elms += item;

      if (x < 3) firstThreeElms += item; // Backup the first three items because the last three must be the same
    }
    col.innerHTML = elms + firstThreeElms;
  }
}

/**
 * Called when the start-button is pressed.
 *
 * @param elem The button itself
 */
function spin(elem) {
  if (isSpinning) return;

  if (balance < SPIN_COST) {
    setStatus("Not enough balance. Refresh to restart.", "lose");
    return;
  }

  isSpinning = true;
  balance -= SPIN_COST;
  renderHud(0, ["-", "-", "-", "-", "-"]);

  let duration = BASE_SPINNING_DURATION + randomDuration();

  for (let col of cols) {
    // set the animation duration for each column
    duration += COLUMN_SPINNING_DURATION + randomDuration();
    col.style.animationDuration = duration + "s";
  }

  // disable the start-button
  elem.setAttribute("disabled", true);

  // set the spinning class so the css animation starts to play
  document.getElementById("container").classList.add("spinning");

  // set the result delayed
  // this would be the right place to request the combination from the server
  window.setTimeout(
    function () {
      let outcome = setResult();
      outcome = applyLossRecoveryRule(outcome);

      if (outcome.payout > 0) {
        accumulatedLoss = 0;
      }

      balance += outcome.payout;

      renderHud(outcome.payout, outcome.payline);
      setStatus(
        outcome.message +
          " Bet " +
          formatMoney(SPIN_COST) +
          ", net " +
          formatMoney(outcome.payout - SPIN_COST) +
          ".",
        outcome.type,
      );
    },
    (BASE_SPINNING_DURATION * 1000) / 2,
  );

  window.setTimeout(
    function () {
      // after the spinning is done, remove the class and enable the button again
      document.getElementById("container").classList.remove("spinning");

      if (balance < SPIN_COST) {
        setStatus("Balance is empty. Refresh page to start again.", "lose");
      } else {
        elem.removeAttribute("disabled");
      }

      isSpinning = false;
    }.bind(elem),
    duration * 1000,
  );
}

/**
 * Sets the result items at the beginning and the end of the columns
 */
function setResult() {
  let payline = [];

  for (let col of cols) {
    // generate 3 random items
    let results = [getRandomIcon(), getRandomIcon(), getRandomIcon()];
    payline.push(results[1]);

    let icons = col.querySelectorAll(".icon img");
    // replace the first and last three items of each column with the generated items
    for (let x = 0; x < 3; x++) {
      updateIconImage(icons[x], results[x]);
      updateIconImage(icons[icons.length - 3 + x], results[x]);
    }
  }

  let payoutResult = evaluatePayout(payline);
  return {
    payout: payoutResult.payout,
    message: payoutResult.message,
    type: payoutResult.type,
    payline: payline,
  };
}

function getRandomIcon() {
  return ICONS[Math.floor(Math.random() * ICONS.length)];
}

/**
 * @returns {number} 0.00 to 0.09 inclusive
 */
function randomDuration() {
  return Math.floor(Math.random() * 10) / 100;
}
