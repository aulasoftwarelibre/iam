@scope
Feature: Removing a scope
    In order to remove old projects
    As a sysadmin
    I want to be able to remove a scope

    @application
    Scenario: Removing a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        Then the scope "iam" with name "Identity and Access Management" should not be available
