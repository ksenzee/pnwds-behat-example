<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;
use \Behat\Mink\Mink;
use \Behat\Mink\Session;
use \Behat\Mink\Driver\Selenium2Driver;
use \Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext {

  private static $driver = null;
  protected $browserstackUsername;
  protected $browserstackPassword;
  protected $capabilities;

  public function __construct($params) {
    // If Browserstack info exists in the profile, create a Mink Selenium
    // session pointing to a Browserstack Selenium URL.
    if (isset($params['capabilities'])) {
      $this->setBrowserstackParams($params);
      $host = "http://{$this->browserstackUsername}:{$this->browserstackPassword}@hub.browserstack.com/wd/hub";

      // Reuse the Selenium driver across scenarios.
      if (self::$driver === null) {
        self::$driver = new Selenium2Driver('', $this->capabilities, $host);
      }

      $mink = new Mink(array(
        'selenium2' => new Session(self::$driver),
      ));

      $mink->setDefaultSessionName('selenium2');
      $this->setMink($mink);
      $this->setMinkParameters($params);
    }
    // If no Browserstack info exists in the profile, assume we are using the
    // regular Goutte and Selenium Mink drivers, and let them set themselves
    // up based on the configuration in behat.yml.
  }

  /**
   * Helper function to set up Browserstack credentials and browser/OS info.
   *
   * @param $params
   * @throws Exception
   */
  protected function setBrowserstackParams($params) {
    if (!isset($params['username'])) {
      throw new \Exception("Please specify your Browserstack username as a parameter to the feature context in behat.yml");
    }
    if (!isset($params['password'])) {
      throw new \Exception("Please specify your Browserstack password as a parameter to the feature context in behat.yml");
    }

    $this->browserstackUsername = $params['username'];
    $this->browserstackPassword = $params['password'];
    $this->capabilities = isset($params['capabilities']) ? $params['capabilities'] : null;
  }

  /**
   * @AfterScenario
   *
   * Deletes cookies in the Selenium-controlled browser after each scenario.
   */
  public function after($event) {
    // This try/catch/swallow is useful when sharing this feature context
    // with setups that don't include a shared Browserstack driver.
    try {
      if (isset(self::$driver)) {
        //self::$driver->reset();
      }
    }
    catch (\Exception $e) {

    }
  }

}
