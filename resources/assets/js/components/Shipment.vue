<template>
    <div>
			<h4 v-model="error" v-if="error.length > 0" class="text-center" style="padding-bottom: 80px">{{error}}</h4>
		<div v-if="shipmentStatus == 0">
		<div class="form-row edit-margin">
			<label for="name" class="col-md-1 col-form-label-sm text-md-right">New Item: </label>
			<div class="col-md-4">
				<input v-model="newItemName" id="name" type="text" class="form-control input input-sm">
			</div>
			<div class="text-center">
				<button @click="newItem" type="submit" class="btn btn-custom btn-sm-margin">
					Add
				</button>
			</div>
			<div class="offset-md-3 col-md-2">Items inside: {{ itemCount }}</div>
		</div>
		</div>

		<ul class="list-group">
		<div v-if="itemCount == 0">
			<h4 class="text-center">No items</h4>
			<div class="col-md-2">
				<a :href="deleteLink"><button class="btn btn-custom btn-lg">Delete</button></a>
			</div>
		</div>
		<div v-else-if="shipmentStatus != 0">
			<h3 class="text-center">Shipment is sent!</h3>

			<div v-for="(item, key) in items">
				<li class="row list-row shipment-sent">
				  <p class="col-md-6">{{item.name}}</p>
				  <p class="col-md-2">{{item.id }}</p>
				  <p class="col-md-2">{{item.code}}</p>
				  <input disabled id="name" name="name" type="number" min="0" placeholder="0" class="col-md-1 form-control input-sm">
					<button disabled @click="deleteItem(item.id,key)" type="button" class="btn btn-outline-danger btn-sm col-md-1 list-btn"><p>Remove</p></button>
				</li>
			</div>
			<div class="col-md-2">
					<a :href="deleteLink"><button class="btn btn-custom btn-lg">Delete</button></a>
			</div>
		</div>
		<div v-else>
			<div v-for="(item, key) in items">
				<li class="row list-row">
				  <p class="col-md-6">{{item.name}}</p>
				  <p class="col-md-2">{{item.id }}</p>
				  <p class="col-md-2">{{item.code}}</p>
				  <input id="name" name="name" type="number" min="0" placeholder="0" class="col-md-1 form-control input-sm">
					<button  @click="deleteItem(item.id,key)" type="button" class="btn btn-outline-danger btn-sm col-md-1 list-btn"><p>Remove</p></button>

				</li>
			</div>
			<div class="row">
				<div class="col-md-2">
					<a :href="deleteLink"><button class="btn btn-custom btn-lg">Delete</button></a>
				</div>
				<div class="col-md-2 offset-md-8">
				<button @click="sendShipment" class="btn btn-custom btn-lg">Send</button>
				</div>
			</div>
		</div>
		</ul>


	</div>


</template>

<script>
    export default {
        data() {
            return {
				error: '',
				items: [],
				token: '',
				newItemName: '',
				shipmentStatus: '',
				deleteLink: window.location.href + '/delete'

            }
        },

		computed: {
			itemCount(){
				return this.items.length;
			}
		  },

		methods: {
			deleteItem(id, key){
				axios.delete('https://api.shipments.test-y-sbm.com/item/' + id,{
					headers: {
						"Content-Type": "application/json",
						"Authorization": "Bearer " + this.token
					}
				}).then(response => {
					this.items.splice(key,1);
				}).catch(error => {
					this.error = "Delete item error: " + error.response.data.error
				});
			},

			newItem(){
				var vm = this;
				var now = _.now();
				var newId = now.toString().substr(5,9);
				var path = window.location.pathname;
				var shipmentId = path.substr(10,15);
				var newCode = _.random(100,999) + now.toString().substr(8,9);
				var data = {
						"id": newId,
						"shipment_id": shipmentId,
						"name": vm.newItemName,
						"code": newCode,
					}
				var settings = {
					  "url": "https://api.shipments.test-y-sbm.com/item",
					  "method": "POST",
					  "headers": {
						"Content-Type": "application/json",
						"Authorization": "Bearer " + vm.token
					  },
					  "data":  JSON.stringify(data)
					}

				$.ajax(settings).done(function (response) {
						vm.items.push({
							"id": response.data.id,
							"shipment_id": response.data.shipment_id,
							"name": response.data.name,
							"code": response.data.code,
							"created_at": response.data.created_at,
							"updated_at": response.data.updated_at
						})
					}).catch(error => {
						vm.error = error
					});
			},

			sendShipment(){
				var vm = this;
				var path = window.location.pathname;
				var shipmentId = path.substr(10,15);
				var data = {
						"id": shipmentId,
					}
				var settings = {
					  "url": "https://api.shipments.test-y-sbm.com/" + path + "/send",
					  "method": "POST",
					  "headers": {
						"Content-Type": "application/json",
						"Authorization": "Bearer " + vm.token
					  },
					  "data":  JSON.stringify(data)
					}

				$.ajax(settings).done(function (response) {
						vm.shipmentStatus = 1
					}).catch(error => {
						vm.error = error
					});
			}
		},
		created: function() {
			var vm = this;
			var settings = {
					  "url": "http://1282466.zamphox.web.hosting-test.net/shareToken",
					  "method": "GET",
					  "processData": false,
					}

			$.ajax(settings).done(function (response) {
				var token;
				token = response;
				console.log(token);
				vm.token = token;
				axios.get('https://api.shipments.test-y-sbm.com' + window.location.pathname,{
					headers: {
						"Content-Type": "application/json",
						"Authorization": "Bearer " + token
					}
				}).then(response => {
					vm.items = response.data.data.items;
					vm.shipmentStatus = response.data.data.is_send;
				}).catch(error => {
					vm.error = "Initial loading (created) : " + error.response.data.message
				});
			});

		},
		mounted: function () {
			var loading = document.getElementById("loading");
			loading.parentNode.removeChild(loading);
            },
}



</script>