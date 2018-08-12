@scope @api
Feature: Updating a scope
    In order to update the scope information
    As a sysadmin
    I want to be able to change some attributes except alias

    @application
    Scenario: Renaming a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I rename it to "AulaSL Identity and Access Management"
        Then it should be renamed to "AulaSL Identity and Access Management"

    Scenario: Renaming a removed scope
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        And I rename it to "AulaSL Identity and Access Management"
        Then I should receive a not found error
