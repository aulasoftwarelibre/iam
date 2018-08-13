@role @api
Feature: Adding roles
    In order to add capabilities to scopes
    As a sysadmin
    I want to be able to create roles

    @application
    Scenario: Adding a role
        Given the scope "iam" with name "Identity and Access Management"
        When I add a "ROLE_ADMIN" to it
        Then the role "ROLE_ADMIN" in this scope should be available

    Scenario: Adding a role to not existent scope
        When I add a "ROLE_ADMIN" to a non existent scope
        Then I should receive an error

    Scenario: Adding an invalid role name
        Given the scope "iam" with name "Identity and Access Management"
        When I add a "ADMIN" to it
        Then I should receive an error
