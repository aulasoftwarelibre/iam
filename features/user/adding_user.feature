@user @api
Feature: Adding an user
    In order to use the infrastructure
    As an user
    I want to be able to register an account

    @application
    Scenario: Adding an user
        When I register an account with the "johndoe" username and "johndoe@mail.com" email
        Then the user "johndoe" should be available
