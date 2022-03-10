<div class="ml-10">
	<div class="mt-20">
		<h2>1. Contact and Billing Information</h2>
		<div>
			<table>
				<tr>
					<td class="text_bold">Name:</td>
					<td>{{ $data['design']->client->name }}</td>
				</tr>
				<tr>
					<td class="text_bold">Company:</td>
					<td>{{ $data['design']->client->company }}</td>
				</tr>
				<tr>
					<td class="text_bold">Address 1:</td>
					<td>{{ $data['design']->client->address }}</td>
				</tr>
				<tr>
					<td class="text_bold">Address 2:</td>
					<td>{{ $data['design']->client->address_2 }}</td>
				</tr>
				<tr>
					<td class="text_bold">City:</td>
					<td>{{ $data['design']->client->city }}</td>
				</tr>
				<tr>
					<td class="text_bold">Prov/State:</td>
					<td>{{ $data['design']->client->state }}</td>
				</tr>
				<tr>
					<td class="text_bold">Postal Code:</td>
					<td>{{ $data['design']->client->zip }}</td>
				</tr>
				<tr>
					<td class="text_bold">Country:</td>
					<td>{{ $data['design']->client->country }}</td>
				</tr>
				<tr>
					<td class="text_bold">Email:</td>
					<td>{{ $data['design']->client->email }}</td>
				</tr>
				<tr>
					<td class="text_bold">Phone:</td>
					<td>{{ $data['design']->client->phone }}</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clear"></div>
	<div class="mt-20">
		<h2>2. Jersey Details</h2>
		<div>
			<table>
				<tr>
					<td class="text_bold">Type of jerseys:</td>
					<td>{{ $data['design']->type_of_jerseys }}</td>
				</tr>
				<tr>
					<td class="text_bold">Accessory Items:</td>
					<td>{{ $data['design']->accessory_items }}</td>
				</tr>
				<tr>
					<td class="text_bold">Quantity Required:</td>
					<td>{{ $data['design']->quantity_required }}</td>
				</tr>
				<tr>
					<td class="text_bold">Date Required:</td>
					<td>{{ $data['design']->date_required }}</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clear"></div>
	<div class="mt-20">
		<h2>3. Design Details</h2>
		<div>
			<table>
				<tr>
					<td class="text_bold">Description of Design:</td>
					<td>
						{!! $data['design']->description ? nl2br($data['design']->description) : 'No description' !!}
					</td>
				</tr>
				<tr>
					<td class="text_bold">Artwork Placement:</td>
					<td>
						{!! $data['design']->artwork ? nl2br($data['design']->artwork) : 'No artwork' !!}
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clear"></div>
</div>
