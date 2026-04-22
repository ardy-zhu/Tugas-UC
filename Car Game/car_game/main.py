import random
import sys
import asyncio
from pathlib import Path
import pygame


pygame.init()

GAME_WIDTH = 480
HUD_PANEL_WIDTH = 220
SCREEN_WIDTH = GAME_WIDTH + HUD_PANEL_WIDTH
SCREEN_HEIGHT = 720
ROAD_WIDTH = 400
ROAD_LEFT = (GAME_WIDTH - ROAD_WIDTH) // 2
ROAD_RIGHT = ROAD_LEFT + ROAD_WIDTH
LANE_COUNT = 2
LANE_WIDTH = ROAD_WIDTH // LANE_COUNT
FPS = 60
PLAYER_SPEED = 10
ENEMY_SPEED = 5
LINE_SPEED = 8
LINE_HEIGHT = 60
LINE_GAP = 35
BACKGROUND_COLOR = (48, 140, 74)
ROAD_COLOR = (55, 55, 55)
LANE_COLOR = (240, 240, 240)
TEXT_COLOR = (255, 255, 255)
ACCENT_COLOR = (255, 214, 10)
PANEL_BG_COLOR = (20, 26, 38)
PANEL_CARD_COLOR = (33, 41, 56)
PANEL_PADDING = 16
PANEL_CARD_WIDTH = HUD_PANEL_WIDTH - (PANEL_PADDING * 2)
HEADER_CARD_RECT = (GAME_WIDTH + PANEL_PADDING, 16, PANEL_CARD_WIDTH, 170)
HINT_CARD_RECT = (GAME_WIDTH + PANEL_PADDING, 206, PANEL_CARD_WIDTH, 120)
STATUS_CARD_RECT = (GAME_WIDTH + PANEL_PADDING, 346, PANEL_CARD_WIDTH, 190)

BASE_DIR = Path(__file__).resolve().parent
IMAGE_DIR = BASE_DIR / "image"
SOUND_DIR = BASE_DIR / "sound"
PLAYER_IMAGE_PATH = IMAGE_DIR / "Asset Mobil Player.png"
ENEMY_IMAGE_PATH = IMAGE_DIR / "Asset Mobil Enemy.png"
BGM_PATH = SOUND_DIR / "bgm.wav"
CRASH_SOUND_PATH = SOUND_DIR / "crash.wav"
POINT_SOUND_PATH = SOUND_DIR / "point.wav"

screen = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT))
pygame.display.set_caption("Car Game")
clock = pygame.time.Clock()
PANEL_X = GAME_WIDTH
PANEL_TEXT_X = PANEL_X + 28
PANEL_TEXT_WIDTH = 160


def lane_x(lane_index):
    return ROAD_LEFT + lane_index * LANE_WIDTH + (LANE_WIDTH // 2)


def load_car_image(path):
    image = pygame.image.load(path).convert_alpha()
    return pygame.transform.smoothscale(image, (120, 120))


def draw_text_block(surface, value, rect, font_size, color=TEXT_COLOR, line_gap=1.2):
    font = pygame.font.SysFont("arial", font_size)
    lines = value.split("\n")
    line_height = int(font_size * line_gap)
    total_height = line_height * max(1, len(lines))
    start_y = rect.top + max(0, (rect.height - total_height) // 2)

    for idx, line in enumerate(lines):
        text_surface = font.render(line, True, color)
        text_rect = text_surface.get_rect(center=(rect.centerx, start_y + idx * line_height + line_height // 2))
        surface.blit(text_surface, text_rect)


class Player:
    def __init__(self, image):
        self.image = image
        self.rect = self.image.get_rect(midbottom=(lane_x(1), SCREEN_HEIGHT - 30))

    def move(self, direction):
        self.rect.x += direction * PLAYER_SPEED
        self.rect.left = max(self.rect.left, ROAD_LEFT + 10)
        self.rect.right = min(self.rect.right, ROAD_RIGHT - 10)

    def draw(self, surface):
        surface.blit(self.image, self.rect)

    def reset(self):
        self.rect.midbottom = (lane_x(1), SCREEN_HEIGHT - 30)


class Enemy:
    def __init__(self, image):
        self.image = image
        self.rect = self.image.get_rect()
        self.reset()

    def update(self):
        self.rect.y += ENEMY_SPEED
        if self.rect.top > SCREEN_HEIGHT:
            self.reset()
            return True
        return False

    def draw(self, surface):
        surface.blit(self.image, self.rect)

    def reset(self):
        lane_index = random.randint(0, LANE_COUNT - 1)
        self.rect.midtop = (lane_x(lane_index), random.randint(-260, -120))


class Track:
    def __init__(self):
        self.line_offset = 0

    def update(self):
        self.line_offset = (self.line_offset + LINE_SPEED) % (LINE_HEIGHT + LINE_GAP)

    def draw(self, surface):
        surface.fill(BACKGROUND_COLOR)
        pygame.draw.rect(surface, ROAD_COLOR, (ROAD_LEFT, 0, ROAD_WIDTH, SCREEN_HEIGHT))
        pygame.draw.rect(surface, ACCENT_COLOR, (ROAD_LEFT, 0, 6, SCREEN_HEIGHT))
        pygame.draw.rect(surface, ACCENT_COLOR, (ROAD_RIGHT - 6, 0, 6, SCREEN_HEIGHT))
        pygame.draw.rect(surface, PANEL_BG_COLOR, (PANEL_X, 0, HUD_PANEL_WIDTH, SCREEN_HEIGHT))
        pygame.draw.line(surface, ACCENT_COLOR, (PANEL_X, 0), (PANEL_X, SCREEN_HEIGHT), 4)
        pygame.draw.rect(surface, PANEL_CARD_COLOR, HEADER_CARD_RECT, border_radius=14)
        pygame.draw.rect(surface, PANEL_CARD_COLOR, HINT_CARD_RECT, border_radius=14)
        pygame.draw.rect(surface, PANEL_CARD_COLOR, STATUS_CARD_RECT, border_radius=14)

        for lane_number in range(1, LANE_COUNT):
            x_pos = ROAD_LEFT + lane_number * LANE_WIDTH
            y_pos = -LINE_HEIGHT + self.line_offset
            while y_pos < SCREEN_HEIGHT:
                pygame.draw.rect(surface, LANE_COLOR, (x_pos - 4, y_pos, 8, LINE_HEIGHT), border_radius=4)
                y_pos += LINE_HEIGHT + LINE_GAP


class Game:
    def __init__(self):
        player_image = load_car_image(PLAYER_IMAGE_PATH)
        enemy_image = load_car_image(ENEMY_IMAGE_PATH)
        self.track = Track()
        self.player = Player(player_image)
        self.enemy = Enemy(enemy_image)
        self.score = 0
        self.game_over = False
        self.audio_enabled = False
        self.crash_sound = None
        self.point_sound = None

        try:
            pygame.mixer.init()
            pygame.mixer.music.load(str(BGM_PATH))
            pygame.mixer.music.set_volume(0.35)
            self.crash_sound = pygame.mixer.Sound(str(CRASH_SOUND_PATH))
            self.point_sound = pygame.mixer.Sound(str(POINT_SOUND_PATH))
            self.crash_sound.set_volume(0.8)
            self.point_sound.set_volume(0.6)
            pygame.mixer.music.play(-1)
            self.audio_enabled = True
        except pygame.error:
            self.audio_enabled = False

    def update(self, move_direction):
        if self.game_over:
            return

        self.track.update()
        self.player.move(move_direction)
        passed_enemy = self.enemy.update()
        if passed_enemy:
            self.score += 1
            if self.audio_enabled and self.point_sound is not None:
                self.point_sound.play()

        if self.player.rect.colliderect(self.enemy.rect):
            self.game_over = True
            if self.audio_enabled:
                pygame.mixer.music.stop()
                if self.crash_sound is not None:
                    self.crash_sound.play()

    def draw(self, surface):
        self.track.draw(surface)
        self.player.draw(surface)
        self.enemy.draw(surface)
        self.draw_hud(surface)

        if self.game_over:
            self.draw_game_over(surface)

    def draw_hud(self, surface):
        draw_text_block(surface, "CAR GAME", pygame.Rect(PANEL_TEXT_X, 34, PANEL_TEXT_WIDTH, 40), 34)
        draw_text_block(surface, f"Score: {self.score}", pygame.Rect(PANEL_TEXT_X, 92, PANEL_TEXT_WIDTH, 34), 24)
        draw_text_block(surface, "Use Left / Right\nto move", pygame.Rect(PANEL_TEXT_X, 228, PANEL_TEXT_WIDTH, 68), 22)

    def draw_game_over(self, surface):
        overlay = pygame.Surface((GAME_WIDTH, SCREEN_HEIGHT), pygame.SRCALPHA)
        overlay.fill((0, 0, 0, 160))
        surface.blit(overlay, (0, 0))

        draw_text_block(surface, "Game Over", pygame.Rect(PANEL_TEXT_X, 372, PANEL_TEXT_WIDTH, 40), 30)
        draw_text_block(surface, f"Final score: {self.score}", pygame.Rect(PANEL_TEXT_X, 426, PANEL_TEXT_WIDTH, 34), 24)
        draw_text_block(surface, "Press SPACE\nto restart", pygame.Rect(PANEL_TEXT_X, 470, PANEL_TEXT_WIDTH, 68), 22)

    def restart(self):
        self.score = 0
        self.game_over = False
        self.player.reset()
        self.enemy.reset()
        if self.audio_enabled:
            pygame.mixer.music.play(-1)


async def main():
    game = Game()
    move_direction = 0

    while True:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                pygame.quit()
                return

            if event.type == pygame.KEYDOWN:
                if event.key in (pygame.K_LEFT, pygame.K_a):
                    move_direction = -1
                elif event.key in (pygame.K_RIGHT, pygame.K_d):
                    move_direction = 1
                elif event.key == pygame.K_SPACE and game.game_over:
                    game.restart()

            if event.type == pygame.KEYUP:
                if event.key in (pygame.K_LEFT, pygame.K_a, pygame.K_RIGHT, pygame.K_d):
                    move_direction = 0

        game.update(move_direction)
        game.draw(screen)
        pygame.display.update()
        clock.tick(FPS)
        await asyncio.sleep(0)



if __name__ == "__main__":
    asyncio.run(main())