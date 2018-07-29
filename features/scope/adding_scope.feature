@scope
Feature: Adding a scope
    In order to secure many projects
    As a sysadmin
    I want to be able to register one scope by project

    @application
    Scenario: Adding a scope
        When I register an scope with name "Identity and Access Management" and short name "iam"
        Then the scope "iam" with name "Identity and Access Management" should be available

