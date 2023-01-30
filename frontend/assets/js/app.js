(function() {
   
    function Product(name, description, type, suppliers)
    {
        let nameHtml = function() {
            return `<h3 class="name">${name}  - ${type}</h3>`;	
        }

        let descriptionHtml = function() {
            return `<div class="description">${description}</div>`;	
        }

        let supplierHtml = function(supplier) {
            return ` <span class="supplier">${supplier}</span>`;	
        }

        let suppliersHtml = function() {
            var html = '';

            if(!suppliers){
                return html;
            }

            suppliers.forEach(function(supplier){
                html += supplierHtml(supplier);
            });

            return `<div class="suppliers">${html}</div>`;
        }

        this.toHtml = function() {
            let product = '';

            product += nameHtml();
            product += descriptionHtml();
            product += suppliersHtml();

            return `<div class="product">${product}</div>`;
        }
    }

    function Card(products) 
    {
        let productsHtml = function() 
        {
            let html = '';

            products.forEach(function(item){
                console.log(item)
                var product = new Product(
                    item.name,
                    item.description,
                    item.type,
                    item.suppliers
                );

                html += product.toHtml();
            });

            return html;
        }

        this.addToDom = function() {
            document
                .getElementById("products")
                .innerHTML = productsHtml();
        }
    }

    fetch('http://localhost:8000/insurance')
        .then((response) => response.json())
        .then((products) => {
            let productCard = new Card(products);
            productCard.addToDom();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
})();