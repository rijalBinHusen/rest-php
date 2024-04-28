import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore order endpoint test", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");

    it("Should merge 2 order in 1 id group", async () => {

        let id_order_1 = "";
        let id_order_2 = ""

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);
        const phone_order = faker.number.int({ min: 6280000000000, max: 6289999999999 });
        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);
        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_1, "POST", true)
        const responseJSON = await response.json();

        // console.log(responseJSON);
        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // create order 2
        const order_2 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response2 = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_2, "POST", true)
        const responseJSON2 = await response2.json();

        expect(response2.status).equal(201);
        expect(responseJSON2.success).equal(true);
        expect(responseJSON2.id).not.equal("");
        id_order_2 = responseJSON2.id

        // merge order
        const body = { id_order_1, id_order_2 }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(200);
        expect(responseJSONMergeOrder.success).equal(true);
        expect(responseJSONMergeOrder.message).equal("Order merged");

        // Get both order
        // Order 1
        const getOrder1 = await fetchReq.doFetch("binhusenstore/order/" + id_order_1, false, "GET", true, true)
        const responseGetOrder1 = await getOrder1.json();

        expect(getOrder1.status).equal(200);
        expect(responseGetOrder1.success).equal(true)

        // Order 2
        const getOrder2 = await fetchReq.doFetch("binhusenstore/order/" + id_order_2, false, "GET", true, true)
        const responseGetOrder2 = await getOrder2.json();

        expect(getOrder2.status).equal(200);
        expect(responseGetOrder2.success).equal(true)

        // compare both order
        expect(responseGetOrder1.data[0].id_group).equal(responseGetOrder2.data[0].id_group)
    })

    it("Should add id order group 1 to order 2", async () => {

        let id_order_1 = "";
        let id_order_2 = ""

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);
        const phone_order = faker.number.int({ min: 6280000000000, max: 6289999999999 });
        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: faker.string.sample(9),
            is_group: true,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment", order_1, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // create order 2
        const order_2 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response2 = await fetchReq.doFetch("binhusenstore/order_also_payment", order_2, "POST", true)
        const responseJSON2 = await response2.json();

        expect(response2.status).equal(201);
        expect(responseJSON2.success).equal(true);
        expect(responseJSON2.id).not.equal("");
        id_order_2 = responseJSON2.id

        // merge order
        const body = { id_order_1, id_order_2 }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(200);
        expect(responseJSONMergeOrder.success).equal(true);
        expect(responseJSONMergeOrder.message).equal("Order merged");

        // Get both order
        // Order 1
        const getOrder1 = await fetchReq.doFetch("binhusenstore/order/" + id_order_1, false, "GET", true, true)
        const responseGetOrder1 = await getOrder1.json();

        expect(getOrder1.status).equal(200);
        expect(responseGetOrder1.success).equal(true)

        // Order 2
        const getOrder2 = await fetchReq.doFetch("binhusenstore/order/" + id_order_2, false, "GET", true, true)
        const responseGetOrder2 = await getOrder2.json();

        expect(getOrder2.status).equal(200);
        expect(responseGetOrder2.success).equal(true)

        // compare both order
        expect(responseGetOrder1.data[0].id_group).equal(responseGetOrder2.data[0].id_group)
        expect(order_1.id_group).equal(responseGetOrder2.data[0].id_group)
    })

    it("Should add id order group 2 to order 1", async () => {

        let id_order_1 = "";
        let id_order_2 = ""

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);
        const phone_order = faker.number.int({ min: 6280000000000, max: 6289999999999 });
        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_1, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // create order 2
        const order_2 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: faker.string.sample(9),
            is_group: true,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response2 = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_2, "POST", true)
        const responseJSON2 = await response2.json();

        expect(response2.status).equal(201);
        expect(responseJSON2.success).equal(true);
        expect(responseJSON2.id).not.equal("");
        id_order_2 = responseJSON2.id

        // merge order
        const body = { id_order_1, id_order_2 }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(200);
        expect(responseJSONMergeOrder.success).equal(true);
        expect(responseJSONMergeOrder.message).equal("Order merged");

        // Get both order
        // Order 1
        const getOrder1 = await fetchReq.doFetch("binhusenstore/order/" + id_order_1, false, "GET", true, true)
        const responseGetOrder1 = await getOrder1.json();

        expect(getOrder1.status).equal(200);
        expect(responseGetOrder1.success).equal(true)

        // Order 2
        const getOrder2 = await fetchReq.doFetch("binhusenstore/order/" + id_order_2, false, "GET", true, true)
        const responseGetOrder2 = await getOrder2.json();

        expect(getOrder2.status).equal(200);
        expect(responseGetOrder2.success).equal(true)

        // compare both order
        expect(responseGetOrder1.data[0].id_group).equal(responseGetOrder2.data[0].id_group)
        expect(order_2.id_group).equal(responseGetOrder1.data[0].id_group)
    })

    it("Merge order error 400", async () => {
        const body = {
            id_order_1: "Failed",
            id_order_2: " Failed"
        }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(400);
        expect(responseJSONMergeOrder.success).equal(false);
        expect(responseJSONMergeOrder.message).equal("Failed to merge order, check the data you sent!");
    })

    it("Merge order error 401 non authenticated", async () => {
        const body = {
            id_order_1: faker.string.sample(9),
            id_order_2: faker.string.sample(9)
        }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT")
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(401);
        expect(responseJSONMergeOrder.success).equal(false);
        expect(responseJSONMergeOrder.message).equal("You must be authenticated to access this resource.");
    })

    it("Merge order error 404 not found, 1 order not found", async () => {

        let id_order_1 = "";

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);
        const phone_order = faker.number.int({ min: 6280000000000, max: 6289999999999 });
        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_1, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // merge order
        const body = { id_order_1, id_order_2: "G23456789" }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(404);
        expect(responseJSONMergeOrder.success).equal(false);
        expect(responseJSONMergeOrder.message).equal("Order not found");
    })


    it("Merge order but each order has id group", async () => {

        let id_order_1 = "";
        let id_order_2 = ""

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);

        const phone_order = faker.number.int({ min: 6280000000000, max: 6289999999999 });
        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: faker.string.sample(9),
            is_group: true,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_1, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // create order 2
        const order_2 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: faker.string.sample(9),
            is_group: true,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: phone_order,
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response2 = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_2, "POST", true)
        const responseJSON2 = await response2.json();

        expect(response2.status).equal(201);
        expect(responseJSON2.success).equal(true);
        expect(responseJSON2.id).not.equal("");
        id_order_2 = responseJSON2.id

        // merge order
        const body = { id_order_1, id_order_2 }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(400);
        expect(responseJSONMergeOrder.success).equal(false);
        expect(responseJSONMergeOrder.message).equal("Semua order telah memiliki group masing masing");
    })

    it("Merge order but phone not same", async () => {

        let id_order_1 = "";
        let id_order_2 = ""
        
        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);

        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // create order 1
        const order_1 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: faker.string.sample(9),
            is_group: true,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: faker.number.int({ min: 6280000000000, max: 6289999999999 }),
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_1, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        id_order_1 = responseJSON.id

        // create order 2
        const order_2 = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: faker.number.int({ min: 6280000000000, max: 6289999999999 }),
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        const response2 = await fetchReq.doFetch("binhusenstore/order_also_payment/", order_2, "POST", true)
        const responseJSON2 = await response2.json();

        expect(response2.status).equal(201);
        expect(responseJSON2.success).equal(true);
        expect(responseJSON2.id).not.equal("");
        id_order_2 = responseJSON2.id

        // merge order
        const body = { id_order_1, id_order_2 }
        const responseMergeOrder = await fetchReq.doFetch("binhusenstore/orders/merge/add_id_group", body, "PUT", true)
        const responseJSONMergeOrder = await responseMergeOrder.json();

        expect(responseMergeOrder.status).equal(400);
        expect(responseJSONMergeOrder.success).equal(false);
        expect(responseJSONMergeOrder.message).equal("Nomor handphone pemesan tidak sama");
    })
})