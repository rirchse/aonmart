<table class="table table-hover containerItems">
  <thead class="sticky-top bg-gray-200">
  <tr>
    <th style="padding: 0.75rem 8px; width: 15%">Image</th>
    <th style="padding: 0.75rem 8px; width: 55%">Name</th>
    <th style="padding: 0.75rem 8px; width: 15%" class="text-center">Price</th>
    <th style="padding: 0.75rem 8px; width: 15%" class="text-center">In Stock</th>
  </tr>
  </thead>
  <tbody class="containerItems" style="height: 100%">
  @forelse ($products as $product)
    <tr
      style="margin:0;"
      id="product-{{ $product->id }}"
      class="add-product-btn cursor-pointer"
      data-toggle="tooltip" data-theme="dark"
      data-name="{{ $product->name }}"
      data-id="{{ $product->id }}"
      data-price="{{ $product->price }}"
      data-stock="{{ $product->store_in_stock }}"
    >
      <td class="align-middle">
        <img class="img-fluid w-100" src="{{ $product->image_url }}" alt="{{ $product->name }}">
      </td>
      <td class="align-middle">
        <p class="text-justify mb-0">{{ $product->name }}</p>
      </td>
      <td class="text-center align-middle">
        <p class="text-truncate mb-0">{{ $product->formatted_price }}</p>
      </td>
      <td class="text-center align-middle">
        <p class="w-100 text-truncate mb-0">{{ $product->formatted_store_in_stock }}</p>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="4" class="text-center">No Product Found</td>
    </tr>
  @endforelse
  </tbody>
</table>
