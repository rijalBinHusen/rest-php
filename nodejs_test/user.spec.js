import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("User end point test", () => {

    const fetchReq = new FetchRequest();

    it("Register failed because user exists", async () => {

        const body = {
            "email": "test@test.com",
            "password": "12323",
            "name": "sdkfjsldkfj"
        }

        const response = await fetchReq.doFetch("user/register", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(409);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("User exist.");
    })

    it("Register failed because not contain name", async () => {

        const body = { email: faker.string + "@test.com", password: '123123' };
        const response = await fetchReq.doFetch("user/register", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(422);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Unprocessable Entity");
    })

    const newEmailAccount = faker.string.uuid() + "@test.com";
    const newPasswordAccount = faker.number.int({ min: 100001 });
    const newNameAccount = faker.person.firstName();

    it("New account should be registered", async () => {

        const body = { email: newEmailAccount, password: newPasswordAccount, name: newNameAccount };
        const response = await fetchReq.doFetch("user/register", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Registration success.");
    })

    it("Login new account should be success", async () => {

        const body = { email: newEmailAccount, password: newPasswordAccount };
        const response = await fetchReq.doFetch("user/login", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.token).not.equal("");
    })

    it("Login with wrong password", async () => {
        const body = { email: newEmailAccount, password: 'sdflkj123' };
        const response = await fetchReq.doFetch("user/login", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Invalid user/password");
    })

    it("Validate token", async () => {
        const response = await fetchReq.doFetch("user/validate", false, "POST");
        const responseJSON = await response.json();
        
        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Valid token");
    })

})