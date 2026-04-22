import random
import sys
from pathlib import Path

import pygame


pygame.init()

SCREEN_WIDTH = 480
SCREEN_HEIGHT = 720
ROAD_WIDTH = 400
ROAD_LEFT = (SCREEN_WIDTH - ROAD_WIDTH) // 2
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

BASE_DIR = Path(__file__).resolve().parent
IMAGE_DIR = BASE_DIR / "image"
PLAYER_IMAGE_PATH = IMAGE_DIR / "Asset Mobil Player.png"
ENEMY_IMAGE_PATH = IMAGE_DIR / "Asset Mobil Enemy.png"

screen = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT))
pygame.display.set_caption("Car Game")
clock = pygame.time.Clock()
title_font = pygame.font.SysFont("arial", 34, bold=True)
info_font = pygame.font.SysFont("arial", 24)


def lane_x(lane_index):
    return ROAD_LEFT + lane_index * LANE_WIDTH + (LANE_WIDTH // 2)


def load_car_image(path):
    image = pygame.image.load(path).convert_alpha()
    return pygame.transform.smoothscale(image, (120, 120))


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

    def update(self, move_direction):
        if self.game_over:
            return

        self.track.update()
        self.player.move(move_direction)
        passed_enemy = self.enemy.update()
        if passed_enemy:
            self.score += 1

        if self.player.rect.colliderect(self.enemy.rect):
            self.game_over = True

    def draw(self, surface):
        self.track.draw(surface)
        self.player.draw(surface)
        self.enemy.draw(surface)
        self.draw_hud(surface)

        if self.game_over:
            self.draw_game_over(surface)

    def draw_hud(self, surface):
        title_text = title_font.render("CAR GAME", True, TEXT_COLOR)
        score_text = info_font.render(f"Score: {self.score}", True, TEXT_COLOR)
        hint_text = info_font.render("A/D or Left/Right to move", True, TEXT_COLOR)
        surface.blit(title_text, (20, 20))
        surface.blit(score_text, (20, 65))
        surface.blit(hint_text, (20, 98))

    def draw_game_over(self, surface):
        overlay = pygame.Surface((SCREEN_WIDTH, SCREEN_HEIGHT), pygame.SRCALPHA)
        overlay.fill((0, 0, 0, 160))
        surface.blit(overlay, (0, 0))

        game_over_text = title_font.render("Game Over", True, TEXT_COLOR)
        restart_text = info_font.render("Press SPACE to restart", True, TEXT_COLOR)
        score_text = info_font.render(f"Final score: {self.score}", True, TEXT_COLOR)
        surface.blit(game_over_text, game_over_text.get_rect(center=(SCREEN_WIDTH // 2, 280)))
        surface.blit(score_text, score_text.get_rect(center=(SCREEN_WIDTH // 2, 330)))
        surface.blit(restart_text, restart_text.get_rect(center=(SCREEN_WIDTH // 2, 370)))

    def restart(self):
        self.score = 0
        self.game_over = False
        self.player.reset()
        self.enemy.reset()


def main():
    game = Game()
    move_direction = 0

    while True:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                pygame.quit()
                sys.exit()

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


if __name__ == "__main__":
    main()