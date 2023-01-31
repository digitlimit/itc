(function() {
   
    /**
     * The product class
     * Handles the creation of HTML template for product lists
     * 
     * @param {*} name 
     * @param {*} description 
     * @param {*} type 
     * @param {*} suppliers 
     */
    function Product(name, description, type, suppliers)
    {
        let nameHtml = function() {
            type = type ? ' - ' + type : '';
            return `<h3 class="name">${name} ${type}</h3>`;	
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

    /**
     * Handles the card display
     * 
     * @param {*} products 
     */
    function Card(products) 
    {
        let productsHtml = function() 
        {
            let html = '';

            products.forEach(function(item){
                // console.log(item)
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

    /**
     * Make API call 
     * 
     * NB: This fetch command is not supported in all browsers
     */
    function API(baseUrl) 
    {
        /**
         * Build the request url
         * 
         * @param {*} path 
         * @param {*} params 
         * @returns 
         */
        let getUrl = function(path, params) 
        {
            // remove leading slash
            path = path.replace(/^\/+/g, '');

            // build url
            let url = new URL(baseUrl + '/' + path);

            // build params if exists
            if(typeof params === 'object' 
                && params !== null 
                && Object.keys(params).length !== 0
            ) {
                url += '?' + new URLSearchParams(params);
            }

            return url;
        }

        /**
         * Perform GET request 
         * 
         * @param {*} path 
         * @param {*} params 
         * @param {*} successCallback 
         * @param {*} errorCallback 
         */
        this.get = function(path, params, successCallback, errorCallback) 
        {
            fetch(getUrl(path, params))
                .then((response) => response.json())
                .then((response) => successCallback(response))
                .catch((error)   => errorCallback(error));
        }
    }

    /**
     * Make API call 
     * 
     * NB: This fetch command is not supported in all browsers
     */
    let baseUrl = 'http://localhost:8000';
    let api = new API(baseUrl);

    api.get('insurance', {}, function(products){
        let productCard = new Card(products);
        productCard.addToDom();
    }, function(error) {
        console.error('Error:', error);
    });
})();