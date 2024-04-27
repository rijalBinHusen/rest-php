import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore order endpoint test", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");

    const newOrder = {
        date_order: faker.date.past(),
        id_group: "",
        is_group: false,
        id_product: faker.string.sample(9),
        name_of_customer: faker.person.firstName(),
        sent: false,
        title: faker.color(),
        total_balance: faker.number.int({ min: 700000 }),
        phone: faker.phone.number(),
        admin_charge: true
    }

    let idOrderCreated = ""

    it("New order should be created", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id
    })

    it("Failed create new order because request body invalid", async () => {

        const body = {
            date_order: "Failed",
            id_group: "Failed",
            is_group: "Failed",
            id_product: "Failed",
            name_of_customer: "Failed",
            sent: "Failed",
            title: "Failed",
            total_balance: "Failed",
            phone: "Failed",
            admin_charge: "Failed"
        }

        const response = await fetchReq.doFetch("binhusenstore/order", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add order, check the data you sent");

    })

    it("Failed create new order because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

    it("Should get the order", async () => {

        const response = await fetchReq.doFetch("binhusenstore/orders", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data[0]).haveOwnProperty("date_order");
        expect(responseJSON.data[0]).haveOwnProperty("id_group");
        expect(responseJSON.data[0]).haveOwnProperty("is_group");
        expect(responseJSON.data[0]).haveOwnProperty("name_of_customer");
        expect(responseJSON.data[0]).haveOwnProperty("sent");
        expect(responseJSON.data[0]).haveOwnProperty("title");
        expect(responseJSON.data[0]).haveOwnProperty("total_balance");
        expect(responseJSON.data[0]).haveOwnProperty("phone");
        expect(responseJSON.data[0]).haveOwnProperty("admin_charge");

    })

    it("Failed get order because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/orders", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Should get order by Id", async () => {
        const response = await fetchReq("binhusenstore/order/" + idOrderCreated, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        // expect(responseJSON.data.admin_charge).equal(newOrder.admin_charge);
        expect(responseJSON.data.date_order).equal(newOrder.date_order);
        expect(responseJSON.data.id_group).equal(newOrder.id_group);
        expect(responseJSON.data.is_group).equal(newOrder.is_group);
        expect(responseJSON.data.id_product).equal(newOrder.id_product);
        expect(responseJSON.data.name_of_customer).equal(newOrder.name_of_customer);
        expect(responseJSON.data.sent).equal(newOrder.sent);
        expect(responseJSON.data.title).equal(newOrder.title);
        expect(responseJSON.data.total_balance).equal(newOrder.total_balance);
    })


    it("Failed get order by id because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })


    it("Order by id not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/aaaa", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Put Order by id should be success", async () => {

        const body = { title: "Updated" }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update order success");
    })

    it("Put Order by id failed", async () => {

        const body = { date_order: "Failed" }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Failed to update order, check the data you sent");
    })

    it("Put Order by id failed", async () => {

        const body = { title: "Failed" }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT")
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Put Order by id not found", async () => {

        const body = { title: "Failed" }

        const response = await fetchReq.doFetch("binhusenstore/order/aaaaa", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Order should be removed", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "DELETE", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Delete order success");
    })

    it("Remove order non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "DELETE")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Remove order error 404 not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/aaaaa", false, "DELETE", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Move order to archive error 401 non authencticated", async () => {

        const body = {
            id_order: "123456789",
            phone: "0987654321"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Move order to archive error 400 invalid phone number", async () => {

        const body = {
            id_order: "123456789",
            phone: "a;sdjfsl;dfskdjfhskdfkj"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to archive order, check the data you sent");
    })

    it("Move order to archive error 400 invalid id order", async () => {

        const body = {
            id_order: "123456789",
            phone: "0987654321"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Move order to archive error 400 phone number not same", async () => {

        const newOrder = {
            date_order: faker.date.past(),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: false,
            title: faker.color(),
            total_balance: faker.number.int({ min: 700000 }),
            phone: faker.phone.number(),
            admin_charge: true
        }

        let idOrderCreated = ""

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id

        const body = {
            id_order: idOrderCreated,
            phone: 234567245
        }

        const responseMoveToArchive = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseMoveToArchiveJSON = await responseMoveToArchive.json();

        expect(responseMoveToArchive.status).equal(201);
        expect(responseMoveToArchiveJSON.success).equal(true);
        expect(responseMoveToArchiveJSON.id).not.equal("");
        idOrderCreated = responseMoveToArchiveJSON.id
    })

    it("Move order to archive success", async () => {

        const newOrder = {
            date_order: faker.date.past(),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: false,
            title: faker.color(),
            total_balance: faker.number.int({ min: 700000 }),
            phone: faker.phone.number(),
            admin_charge: true
        }

        let idOrderCreated = ""

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id

        const body = {
            id_order: idOrderCreated,
            phone: 234567245
        }

        const responseMoveToArchive = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseMoveToArchiveJSON = await responseMoveToArchive.json();

        expect(responseMoveToArchive.status).equal(201);
        expect(responseMoveToArchiveJSON.success).equal(true);
        expect(responseMoveToArchiveJSON.id).not.equal("");
        idOrderCreated = responseMoveToArchiveJSON.id
    })
})