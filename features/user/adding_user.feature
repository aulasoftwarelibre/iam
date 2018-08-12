@user @api
Feature: Adding an user
    In order to use the infrastructure
    As an user
    I want to be able to register an account

    @application
    Scenario: Adding an user
        When I register an account with username "johndoe"
        Then the user "johndoe" should be available
