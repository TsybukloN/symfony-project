<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context
{
    /**
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($role)
    {
        $this->visit('/login');
        $this->fillField('_username', 'admin@example.com');
        $this->fillField('_password', 'admin_password');
        $this->pressButton('Login');
    }

    /**
     * @When /^I am on "([^"]*)"$/
     */
    public function iNavigateTo($url)
    {
        $this->visit($url);
    }

    /**
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSeeTheText($text)
    {
        $this->assertPageContainsText($text);
    }
}
