@role @api
Feature: Show role details
    In order to get all information about a role
    As a sysadmin
    I want to see role details

    Scenario: Get details from a role
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_USER" from this scope
        When I check its details
        Then I should see than the role name is "ROLE_USER"
