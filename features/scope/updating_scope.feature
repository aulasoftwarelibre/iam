@scope
Feature: Updating a scope
    In order to update the scope information
    As a sysadmin
    I want to be able to change some attributes except short name

    @application
    Scenario: Renaming a scope
        Given the scope "iam" with name "Identity and Access Management"
        When I rename it to "AulaSL Identity and Access Management"
        Then it should be renamed to "AulaSL Identity and Access Management"

