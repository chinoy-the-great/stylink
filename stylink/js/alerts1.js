(function ($) {
  showSwal = function (type, productData = null) { // Allow passing product data
      "use strict";

      if (type === "edit_product") {
          swal({
              title: productData ? "Edit Product" : "Add New Product", 
              html: `
                  <label for="product_id">Product ID:</label>
                  <input id="product_id" type="text" class="swal2-input" value="${productData ? productData.product_id : ''}" ${productData ? 'readonly' : ''}>

                  <label for="product_name">Product Name:</label>
                  <input id="product_name" type="text" class="swal2-input" value="${productData ? productData.product_name : ''}">

                  <label for="product_price">Product Price:</label>
                  <input id="product_price" type="number" class="swal2-input" value="${productData ? productData.product_price : ''}">

                  <label for="product_stocks">Product Stocks:</label>
                  <input id="product_stocks" type="number" class="swal2-input" value="${productData ? productData.product_stocks : ''}">
              `,
              focusConfirm: false,
              preConfirm: () => {
                  return {
                      product_id: $('#product_id').val(),
                      product_name: $('#product_name').val(),
                      product_price: $('#product_price').val(),
                      product_stocks: $('#product_stocks').val(),
                  };
              }
          }).then((result) => {
              if (result.value) {
                  // Handle form submission (send data to your backend)
                  console.log(result.value); // Example: Log the updated product data
              }
          });
      }
  };
})(jQuery);
