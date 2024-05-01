import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore payment endpoint test", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");


    let idPaymentCreated = ""

    const newpaymentGlobal = {
        date_payment: faker.date.past().toISOString().slice(0, 10),
        id_order: faker.string.sample(9),
        balance: faker.number.int({ min: 700, max: 10000 }),
        id_order_group: ""
    }

    it("New payment should be created", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment", newpaymentGlobal, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idPaymentCreated = responseJSON.id
    })

    it("Create new payment error 400 invalid balance", async () => {
        const newpayment = {
            date_payment: faker.date.past().toISOString().slice(0, 10),
            id_order: faker.string.sample(9),
            balance: "Not number",
            id_order_group: ""
        }

        const response = await fetchReq.doFetch("binhusenstore/payment", newpayment, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add payment, check the data you sent");
    })

    it("Create new payment error 400 invalid date", async () => {
        const newpayment = {
            date_payment: "2023-02-30",
            id_order: faker.string.sample(9),
            balance: faker.number.int({ min: 700, max: 10000 }),
            id_order_group: ""
        }

        const response = await fetchReq.doFetch("binhusenstore/payment", newpayment, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add payment, check the data you sent");
    })

    it("Create new payment error 400 invalid date 2", async () => {
        const newpayment = {
            date_payment: "asdkjflsdjfsdf",
            id_order: faker.string.sample(9),
            balance: faker.number.int({ min: 700, max: 10000 }),
            id_order_group: ""
        }

        const response = await fetchReq.doFetch("binhusenstore/payment", newpayment, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add payment, check the data you sent");
    })

    it("Create new payment error 400 invalid request body", async () => {
        const newpayment = {
            date_payment: "asdkjflsdjfsdf",
            id_order: "1293873",
            balance: "Failded",
            id_order_group: ""
        }

        const response = await fetchReq.doFetch("binhusenstore/payment", newpayment, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add payment, check the data you sent");
    })

    it("New payment error 401 non authenticated", async () => {
        const newpayment = {
            date_payment: faker.date.past().toISOString().slice(0, 10),
            id_order: faker.string.sample(9),
            balance: faker.number.int({ min: 700, max: 10000 }),
            id_order_group: ""
        }

        const response = await fetchReq.doFetch("binhusenstore/payment", newpayment, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Should get payment", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payments?id_order=" + newpaymentGlobal.id_order, "", "GET", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data[0].date_payment).equal(newpaymentGlobal.date_payment);
        expect(responseJSON.data[0].id_order).equal(newpaymentGlobal.id_order);
        expect(responseJSON.data[0].balance).equal(newpaymentGlobal.balance);
    })

    it("Get payment erro 401 non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payments?id_order=" + newpaymentGlobal.id_order, "", "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Get payment erro 404 Not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payments?id_order=mmmmmmmmm", "", "GET", false, true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Payments not found");
    })

    it("Get payment by Id", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/" + idPaymentCreated, "", "GET", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data[0].date_payment).equal(newpaymentGlobal.date_payment);
        expect(responseJSON.data[0].id_order).equal(newpaymentGlobal.id_order);
        expect(responseJSON.data[0].balance).equal(newpaymentGlobal.balance);
    })

    it("Get payment by Id failed 401 non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/" + idPaymentCreated, "", "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Get payment by Id failed 404 not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/sldkjfslkdjf", "", "GET", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Payment not found");
    })

    it("Remove payment by Id", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/" + idPaymentCreated, "", "DELETE", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Delete payment success");
    })

    it("Remove payment by Id 401 not authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/" + idPaymentCreated, "", "DELETE")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Remove payment by Id 404 not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/payment/" + idPaymentCreated, "", "DELETE", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Payment not found");
    })

})