@scope @api
Feature: Browsing scopes
    In order to get all available scopes
    As a sysadmin
    I want to browse all available scopes

    Scenario: Browsing scopes
        Given the scope "iam" with name "Identity and Access Management"
        When I browse the scopes
        Then I should see the "iam" scope
