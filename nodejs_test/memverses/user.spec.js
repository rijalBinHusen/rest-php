import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Memverses user end point test", () => {

    const fetchReq = new FetchRequest();
    const newAccount = { email: faker.string.sample({ max: 13 }) + "@test.com", password: faker.number.int({ min: 100000, max: 999999 }), name: faker.person.firstName() };

    it("New account should be registered", async () => {

        const response = await fetchReq.doFetch("memverses/user/register", newAccount, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Registration success.");
    })

    it("Register failed because user exists", async () => {

        const response = await fetchReq.doFetch("memverses/user/register", newAccount, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(409);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("User exist.");
    })

    it("Register failed because not contain name", async () => {

        const body = { email: faker.string.sample({ max: 10 }) + "@test.com", password: '123123' };
        const response = await fetchReq.doFetch("memverses/user/register", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(422);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Unprocessable Entity");
    })

    it("Login new account should be success", async () => {

        const response = await fetchReq.doFetch("memverses/user/login", newAccount, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.token).not.equal("");
    })

    it("Login with Invalid user/password", async () => {
        const body = { email: newAccount.email, password: 'sdflkj123' };
        const response = await fetchReq.doFetch("memverses/user/login", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Invalid user/password");
    })

    it("Validate token", async () => {
        const response = await fetchReq.doFetch("memverses/user/validate", false, "POST", true);
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Valid token");
    })

    it("Update password failed, because wrong old password", async () => {
        const body = { password_old: 'sdflkj123', password_new: "sdlkkfjsldkfj" };
        const response = await fetchReq.doFetch("memverses/user/login", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Invalid user/password");
    })

    it("Update password failed, because no new password", async () => {
        const body = { password_old: newAccount.password, password_new: "" };
        const response = await fetchReq.doFetch("memverses/user/login", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Invalid user/password");
    })

    it("Update password success", async () => {
        const body = { password_old: newAccount.password, password_new: "123456" };
        const response = await fetchReq.doFetch("memverses/user/update_password", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update password success.");
    })
})