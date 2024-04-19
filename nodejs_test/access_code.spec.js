import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("User end point test", () => {

    const fetchReq = new FetchRequest();
    const newCode = faker.number.int({ min: 10000 })

    it("Should be create new access code", async () => {

        await fetchReq.loginAdmin();

        const body = { 
            'source_name': 'tests',
            'code':  newCode
        }

        const response = await fetchReq.doFetch("access_code/create", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Your code is set");
    })

    it("Failed crete new access code", async () => {

        await fetchReq.loginAdmin();

        const body = { 'source_name': 'tests' }
        const response = await fetchReq.doFetch("access_code/create", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Request body invalid");
    })

    it("Failed validate access code", async () => {

        await fetchReq.loginAdmin();

        const body = { 'source_name': 'tests' }
        const response = await fetchReq.doFetch("access_code/validate", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Request body invalid");
    })

    it("Ivalid access code", async () => {

        await fetchReq.loginAdmin();

        const body = { 'source_name': 'tests', code: "sldkjflskd" }
        const response = await fetchReq.doFetch("access_code/validate", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Access code or resorce name invalid");
    })

    it("Ivalid source name", async () => {

        await fetchReq.loginAdmin();

        const body = { 'source_name': 'tests4444', code: newCode }
        const response = await fetchReq.doFetch("access_code/validate", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Access code or resorce name invalid");
    })

    it("Access code should be valid", async () => {

        await fetchReq.loginAdmin();

        const body = { 'source_name': 'tests', code: newCode }
        const response = await fetchReq.doFetch("access_code/validate", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Your code is valid");
    })
})