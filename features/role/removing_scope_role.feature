@role @application
Feature: Removing a role
    In order to clean unused roles
    As a sysadmin
    I want to be able to remove a role

    Scenario: Removing a scope
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_ADMIN" from this scope
        When I remove it
        Then the role should not be available
