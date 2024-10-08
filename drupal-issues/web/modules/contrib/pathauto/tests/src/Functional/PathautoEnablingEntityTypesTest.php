<?php

namespace Drupal\Tests\pathauto\Functional;

use Drupal\comment\Tests\CommentTestTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests pathauto settings form.
 *
 * @group pathauto
 */
class PathautoEnablingEntityTypesTest extends BrowserTestBase {

  use PathautoTestHelperTrait;

  use CommentTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['node', 'pathauto', 'comment'];

  /**
   * Admin user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'article']);
    $this->addDefaultCommentField('node', 'article');

    $permissions = [
      'administer pathauto',
      'administer url aliases',
      'bulk delete aliases',
      'bulk update aliases',
      'create url aliases',
      'administer nodes',
      'post comments',
    ];
    $this->adminUser = $this->drupalCreateUser($permissions);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests enabling or disabling alias pattern definition for an entity type.
   *
   * Tests with the comment module, which is not enabled by default.
   */
  public function testEnablingEntityTypes() {
    // Verify that the comment entity type is not available when trying to add
    // a new pattern, nor "broken".
    $this->drupalGet('/admin/config/search/path/patterns/add');
    $this->assertCount(0, $this->cssSelect('option[value = "canonical_entities:comment"]:contains(Comment)'));
    $this->assertCount(0, $this->cssSelect('option:contains(Broken)'));

    // Enable the entity type and create a pattern for it.
    $this->drupalGet('/admin/config/search/path/settings');
    $edit = [
      'enabled_entity_types[comment]' => TRUE,
    ];
    $this->submitForm($edit, "Save configuration");
    $this->createPattern('comment', '/comment/[comment:body]');

    // Create a node, a comment type and a comment entity.
    $node = $this->drupalCreateNode(['type' => 'article']);
    $this->drupalGet('/node/' . $node->id());
    $edit = [
      'comment_body[0][value]' => 'test-body',
    ];
    $this->submitForm($edit, 'Save');

    // Verify that an alias has been generated and that the type can no longer
    // be disabled.
    $this->assertAliasExists(['alias' => '/comment/test-body']);
    $this->drupalGet('/admin/config/search/path/settings');
    $this->assertCount(1, $this->cssSelect('input[name = "enabled_entity_types[comment]"][disabled = "disabled"]'));
  }

}
