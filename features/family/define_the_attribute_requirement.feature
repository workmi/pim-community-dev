Feature: Define the attribute requirement
  In order to ensure product completness when exporting them
  As an administrator
  I need to be able to define which attributes are required or not for a given channel

  Background:
    Given a "footwear" catalog configuration
    And I am logged in as "Peter"
    And I am on the "Boots" family page

  Scenario: Successfully display the attribute requirements
    Given I visit the "Attributes" tab
    Then attribute "name" should be required in channels mobile and tablet
    And attribute "lace_color" should not be required in channels mobile and tablet
    And attribute "side_view" should be required in channel tablet
    And attribute "side_view" should not be required in channel mobile

  @javascript
  Scenario: Successfully make an attribute required for a channel
    Given I visit the "Attributes" tab
    And I switch the attribute "Rating" requirement in channel "Mobile"
    And I save the family
    And I visit the "Attributes" tab
    Then attribute "rating" should be required in channels mobile and tablet

  @javascript
  Scenario: Successfully make an attribute optional for a channel
    Given I visit the "Attributes" tab
    And I switch the attribute "Description" requirement in channel "Tablet"
    And I save the family
    And I visit the "Attributes" tab
    Then attribute "description" should not be required in channels mobile and tablet

  @javascript
  Scenario: Ensure attribute requirement suppression
    Given the following product:
      | sku      | family |
      | BIGBOOTS | Boots  |
    And I launched the completeness calculator
    When I am on the "BIGBOOTS" product page
    And I visit the "Completeness" tab
    Then I should see the completeness summary
    And I should see the completeness:
      | channel    | locale                  | state    | message  | ratio |
      | tablet | English (United States) | warning  | 8 missing values | 11%  |
      | mobile     | English (United States) | warning | 4 missing values     | 20%  |
    And I am on the "Boots" family page
    And I visit the "Attributes" tab
    And I switch the attribute "Rating" requirement in channel "Mobile"
    And I save the family
    When I remove the "Rating" attribute
    Then I should see flash message "Attribute successfully removed from the family"
    When I am on the "BIGBOOTS" product page
    And I visit the "Completeness" tab
    Then I should see the completeness summary
    # And I should see the completeness:
    #   | channel    | locale                  | state    | message  | ratio |
    #   | e-commerce | English (United States) | success  | Complete | 100%  |
    #   | e-commerce | French (France)         | success  | Complete | 100%  |
    #   | mobile     | English (United States) | disabled | none     | none  |
    #   | mobile     | French (France)         | success  | Complete | 100%  |
