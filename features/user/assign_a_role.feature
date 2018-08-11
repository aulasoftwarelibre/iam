@user @application @api
Feature: Manage user roles
    In order to manage what users can do in apps
    As a sysadmin
    I want to be able to assign roles to them

    Scenario: Add a role to the user
        Given an account with username "johndoe" and email "johndoe@mail.com"
        And the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_IAM_USER" from this scope
        When I assign the role to the user
        Then I should see that the user has the role

    Scenario: Remove a role to the user
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_IAM_USER" from this scope
        And an account with username "johndoe" and email "johndoe@mail.com" and this role
        When I remove the role to the user
        Then I shouldn't see that the user has the role

    Scenario: Remove a role from a scope
        Given the scope "iam" with name "Identity and Access Management"
        And the role "ROLE_IAM_USER" from this scope
        And an account with username "johndoe" and email "johndoe@mail.com" and this role
        When I remove the role
        Then I shouldn't see that the user has the role


