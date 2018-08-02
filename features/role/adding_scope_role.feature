@role
Feature: Adding roles
    In order to add capabilities to scopes
    As a sysadmin
    I want to be able to create roles

    Scenario: Adding a role
        Given the scope "iam" with name "Identity and Access Management"
        When I add a "ROLE_ADMIN" to it
        Then the role "ROLE_ADMIN" in this scope should be available


