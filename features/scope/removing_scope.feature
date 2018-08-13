@scope @api
Feature: Removing a scope
    In order to remove old projects
    As a sysadmin
    I want to be able to remove a scope

    @application
    Scenario: Removing a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        Then the scope should not be available

    @application
    Scenario: Removing the roles by cascade
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_ADMIN" from this scope
        When I remove it
        Then the scope should not be available neither the role

    Scenario: Trying to remove a scope twice
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        And I remove it again
        Then I should receive a not found error
