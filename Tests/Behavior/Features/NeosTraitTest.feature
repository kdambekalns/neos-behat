@browser
Feature: NeosTrait Utility Trait

  Background: Import Demo-Content
    Given I imported the site "CRON.DazSite"

  @fixtures
  Scenario: Create a new Page of Type on Path
    Given I create a new Page "my-new-page" of type "CRON.DazSite:News" on path "/news/artikel/2015/10/07"

    When I go to "/news/artikel/2015/10/07/my-new-page"
    Then I should not see "404"
    And I should see "my-new-page"

  @fixtures
  Scenario: Change and read Properties of a newly created Page
    Given I create a new Page "my-new-page" of type "CRON.DazSite:News" on path "/news/artikel/2015/10/07"
    When I set the page properties:
      | title       | My New Title                |
      | mainSection | /news/spektrum              |
      | sections    | /news/recht,/news/pharmazie |
      | date        | 2016-03-29                  |

    And I go to "/news/artikel/2015/10/07/my-new-page"
    Then I should see "My New Title"
    And I should see "29.03.2016"
    But I should not see "my-new-page"

    And I should get the page properties:
      | title            | My New Title                |
      | mainSection      | /news/spektrum              |
      | sections         | /news/recht,/news/pharmazie |
      | date             | 2016-03-29                  |
      | commentCount     | 0                           |
      | isCommentable    | 1                           |

  @fixtures
  Scenario: Hide and unhide the Page
    Given I create a new Page "my-new-page" of type "CRON.DazSite:News" on path "/news/artikel/2015/10/07"

    When I hide the Page
    And I go to "/news/artikel/2015/10/07/my-new-page"
    Then I should see "404"

    When I unhide the Page
    And I reload the page
    Then I should not see "404"

  @fixtures
  Scenario: Move Page
    Given I create a new Page "my-new-page" of type "CRON.DazSite:News" on path "/news/artikel/2015/10/07"

    When I go to "/news/artikel/2015/10/07/my-new-page"
    Then I should see "my-new-page"

    When I move the Page into "/news/artikel/2015/10/06"
    And I reload the page
    Then I should see "404"

    When I go to "/news/artikel/2015/10/06/my-new-page"
    Then I should see "my-new-page"
