@scope @api
Feature: Show scope details
    In order to get all information about a scope
    As a sysadmin
    I want to see scope details

    Scenario: Get details from a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I check its details
        Then I should see than the name is "Identity and Access Management" and the alias "iam"

    Scenario: Get details from a removed scope
        Given the scope "iam" with name "Identity and Access Management"
        When I remove it
        And I check its details
        Then I should receive a not found error
