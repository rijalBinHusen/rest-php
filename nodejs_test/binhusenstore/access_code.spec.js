import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore access code point test", () => {

    const fetchReq = new FetchRequest();
    const newCode = faker.number.int({ min: 1000, max: 9999 })

    it("Should be create new access code", async () => {

        await fetchReq.loginAdmin("binhusen_test@test.com@test.com", "123456", "binhusenstore/user/login");

        const body = { 'code':  newCode }

        const response = await fetchReq.doFetch("binhusenstore/access_code", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Your code is set");
    })

    it("Failed crete new access code", async () => {

        await fetchReq.loginAdmin("binhusen_test@test.com@test.com", "123456", "binhusenstore/user/login");

        const response = await fetchReq.doFetch("binhusenstore/access_code", false, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Request body invalid");
    })

    it("Failed validate access code", async () => {

        await fetchReq.loginAdmin("binhusen_test@test.com@test.com", "123456", "binhusenstore/user/login");

        const response = await fetchReq.doFetch("binhusenstore/access_code/validate", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Invalid access code", async () => {

        await fetchReq.loginAdmin("binhusen_test@test.com@test.com", "123456", "binhusenstore/user/login");

        fetchReq.addHeader("Code-Authorization", "sdfkj")
        const response = await fetchReq.doFetch("binhusenstore/access_code/validate", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Access code or resource name invalid");
    })

    it("Access code should be valid", async () => {

        fetchReq.addHeader("Code-Authorization", newCode)
        await fetchReq.loginAdmin("binhusen_test@test.com@test.com", "123456", "binhusenstore/user/login");

        const response = await fetchReq.doFetch("binhusenstore/access_code/validate", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Your code is valid");
    })
})