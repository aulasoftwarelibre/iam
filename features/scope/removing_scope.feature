@scope @api @application
Feature: Removing a scope
    In order to remove old projects
    As a sysadmin
    I want to be able to remove a scope

    Scenario: Removing a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        Then the scope should not be available

    Scenario: Removing the roles by cascade
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_IAM_ADMIN" from this scope
        When I remove it
        Then the scope should not be available neither the role
