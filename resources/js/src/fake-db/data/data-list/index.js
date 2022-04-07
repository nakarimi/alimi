import mock from "@/fake-db/mock.js";

const data = {
  products: [
    {
      id: 4,
      category: " تیل دیزل",
      name:
        "قرداد  تیل گاز 072DXR6571 - کمیسیون مستقل نظارت برتطبیق قانون اساسی",
      order_status: "delivered",
      popularity: 65,
      price: 199.99
    },

    {
      id: 5,
      category: " تیل دیزل",
      name: "قرداد تیل 0012DXR4571 - وزارت عدليه",
      order_status: "canceled",
      popularity: 87,
      price: 199.99
    },
    {
      id: 6,
      category: "تیل",
      name: "قرداد تیل 0012DXR4581 - وزارت ارشاد، حج و اوقاف",
      order_status: "canceled",
      popularity: 55,
      price: 39.99
    },
    {
      id: 7,
      category: "تیل پطرول",
      name: "قرداد تیل 0012DXR4531 - وزارت دفاع ملى افغانستان",
      order_status: "on_hold",
      popularity: 99,
      price: 39.99
    },
    {
      id: 8,
      category: "تیل پطرول",
      name: "قرداد تیل 0012DXR4511 - وزارت  صحت عامه",
      order_status: "canceled",
      popularity: 91,
      price: 39.99
    },
    {
      id: 10,
      category: "تیل دیزل",
      name: "قرداد تیل 0012DXR4551 - وزارت اقتصاد",
      order_status: "canceled",
      popularity: 64,
      price: 39.99
    },
    {
      id: 1,
      category: "گاز ال پی جی",
      name: "قرداد تیل 0052DXD4591 - وزارت کاروامور اجتماعی",
      order_status: "on_hold",
      popularity: 97,
      price: 69.99
    },
    {
      id: 2,
      category: " تیل پطرول",
      name: "قرداد تیل دیزل 0212DXR4771 - وزارت امور زنان",
      order_status: "delivered",
      popularity: 83,
      price: 69.99
    },
    {
      id: 3,
      category: "موبلایل",
      name: "قرداد تیل پطرول 0032DXR4871 - وزارت امور داخله",
      order_status: "canceled",
      popularity: 57,
      price: 199.99
    },
    {
      id: 9,
      category: "تیل پطرول",
      name: "قرداد تیل دیزل 0012DXR3571 - وزارت معارف",
      order_status: "delivered",
      popularity: 52,
      price: 39.99
    },
    {
      id: 19,
      category: "تیل پطرول",
      name: "قرداد تیل دیزل 0012DXR3571 - وزارت انرژی و آب",
      order_status: "delivered",
      popularity: 52,
      price: 39.99
    }
  ]
};

mock.onGet("/api/data-list/products").reply(() => {
  return [200,JSON.parse(JSON.stringify(data.products)).reverse()];
});

// POST : Add new Item
mock.onPost("/api/data-list/products/").reply(request => {
  // Get event from post data
  const item = JSON.parse(request.data).item;

  const length = data.products.length;
  let lastIndex = 0;
  if (length) {
    lastIndex = data.products[length - 1].id;
  }
  item.id = lastIndex + 1;

  data.products.push(item);

  return [
    201,
    {
      id: item.id
    }
  ];
});

// Update Product
mock.onPost(/\/api\/data-list\/products\/\d+/).reply(request => {
  const itemId = request.url.substring(request.url.lastIndexOf("/") + 1);

  const item = data.products.find(item => item.id == itemId);
  Object.assign(item,JSON.parse(request.data).item);

  return [200,item];
});

// DELETE: Remove Item
mock.onDelete(/\/api\/data-list\/products\/\d+/).reply(request => {
  const itemId = request.url.substring(request.url.lastIndexOf("/") + 1);

  const itemIndex = data.products.findIndex(p => p.id == itemId);
  data.products.splice(itemIndex,1);
  return [200];
});
