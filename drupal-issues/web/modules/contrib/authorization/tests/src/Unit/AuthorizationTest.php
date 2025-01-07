<?php

declare(strict_types=1);

namespace Drupal\Test\authorization\Unit;

use Drupal\authorization\AuthorizationResponse;
use Drupal\authorization\AuthorizationServiceInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once __DIR__ . '/../../../authorization.module';

/**
 * Test authorization module.
 */
class AuthorizationTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setup(): void {
    parent::setUp();

    $container = new ContainerBuilder();

    $string_translation = $this->getStringTranslationStub();
    $container->set('string_translation', $string_translation);

    $this->service = $this->createMock(AuthorizationServiceInterface::class);
    $this->config = $this->createMock(Config::class);
    $this->messenger = $this->createMock(Messenger::class);

    $container->set('authorization.manager', $this->service);
    $container->set('config.factory', $this->config);
    $container->set('messenger', $this->messenger);

    \Drupal::setContainer($container);
  }

  /**
   * Test help hook.
   */
  public function testHelpHook(): void {
    $route_match = $this->createMock(RouteMatchInterface::class);
    $output = authorization_help('help.page.authorization', $route_match);

    $this->assertStringContainsString('<h3>About</h3>', $output);
  }

  /**
   * Test user login hook.
   */
  public function testAuthoricationUserLogin(): void {

    $account = $this->createMock(UserInterface::class);

    $this->service->expects($this->once())
      ->method('setUser')
      ->with($account);

    $this->service->expects($this->once())
      ->method('setAllProfiles');
    $authorization = $this->createMock(AuthorizationResponse::class);
    $authorization->expects($this->once())
      ->method('getMessage')
      ->willReturn('"test message"');
    $this->service->expects($this->once())
      ->method('getProcessedAuthorizations')
      ->willReturn([$authorization]);

    $settings = $this->createMock(ImmutableConfig::class);
    $settings->expects($this->once())
      ->method('get')
      ->with('authorization_message')
      ->willReturn(TRUE);
    $this->config->expects($this->once())
      ->method('get')
      ->with('authorization.settings')
      ->willReturn($settings);

    $this->messenger->expects($this->once())
      ->method('addStatus')
      ->with(t('Done with @authorization', ['@authorization' => '"test message"']), TRUE);

    authorization_user_login($account);
  }

}
