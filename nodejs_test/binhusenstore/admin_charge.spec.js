import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore admin charge point test", async () => {

    
    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");
    const newAdminCharge = faker.number.int({ min: 1000, max: 9999 })

    it("Should be create new admin charge", async () => {

        const body = { 'admin_charge': newAdminCharge }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update admin charge success");
    })

    it("Failed create new admin charge because it's not a number", async () => {
                
        const body = { 'admin_charge': "failed test" }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add admin charge, check the data you sent");

    })

    it("Failed create new admin charge because non authenticated", async () => {

        const body = { 'admin_charge': newAdminCharge }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "POST", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

    it("Should get the admin chage price", async () => {

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.admin_charge).equal(newAdminCharge);

    })

    it("Failed get admin charge because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Update admin charge should be success", async () => {

        await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login")
        const body = { 'admin_charge': faker.number.int({min: 100, max: 99999}) }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update admin charge success");

    })

    it("Failed update admin charge because it's not a number", async () => {
        
        const body = { 'admin_charge': "failed test" }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to update admin charge, check the data you sent");

    })

    it("Failed update admin charge because not authenticated", async () => {

        const body = { 'admin_charge': 60000 }

        const response = await fetchReq.doFetch("binhusenstore/admin_charge", body, "PUT")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

})