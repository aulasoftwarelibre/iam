@role @api
Feature: Browsing roles
    In order to get all roles in a scope
    As a sysadmin
    I want to browse all available roles by scope

    Scenario: Browsing roles
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_USER" from this scope
        When I browse the roles in this scope
        Then I should see the "ROLE_USER" role
