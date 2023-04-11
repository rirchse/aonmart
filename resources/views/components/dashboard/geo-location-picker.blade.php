<div x-data="geoLocations" @changed-select2.window="updateSelectValue($event.detail)">
  <div class="form-group row">
    <div class="col-lg-8 offset-lg-2">
      <label for="division_id"><b>{{ __('Division') }}&nbsp;<span class="text-danger">*</span></b></label>
      <select name="division_id" id="division_id" class="custom-select select2  @error('division_id') is-invalid @enderror" x-model="division_id">
        <option value="">Select Division</option>
        @foreach($divisions as $division)
          <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : null }}>{{ $division->name }}</option>
        @endforeach
      </select>
      @error('division_id')
      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
      @enderror
    </div>
  </div>

  <div class="form-group row">
    <div class="col-lg-8 offset-lg-2">
      <label for="district_id"><b>{{ __('District') }}&nbsp;<span class="text-danger">*</span></b></label>
      <select name="district_id" id="district_id" class="custom-select select2 @error('district_id') is-invalid @enderror" x-model="district_id">
        <option value="">Select District</option>
        <template x-for="districtItem in districts" :key="districtItem.id">
          <option x-text="districtItem.name" :value="districtItem.id" :selected="districtItem.id == district_id"></option>
        </template>
      </select>
      @error('district_id')
      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
      @enderror
    </div>
  </div>

  <div class="form-group row">
    <div class="col-lg-8 offset-lg-2">
      <label for="upazila_id"><b>{{ __('Upazila') }}&nbsp;<span class="text-danger">*</span></b></label>
      <select name="upazila_id" id="upazila_id" class="custom-select select2 @error('upazila_id') is-invalid @enderror" x-model="upazila_id">
        <option value="">Select Upazila</option>
        <template x-for="upazilaItem in upazilas" :key="upazilaItem.id">
          <option x-text="upazilaItem.name" :value="upazilaItem.id" :selected="upazilaItem.id == upazila_id"></option>
        </template>
      </select>
      @error('upazila_id')
      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
      @enderror
    </div>
  </div>
</div>

@push('script')
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    const DISTRICTS_API_URL = '{{ route('api.v1.districts') }}';
    const UPAZILAS_API_URL = '{{ route('api.v1.upazilas') }}';
    document.addEventListener('alpine:init', () => {
      Alpine.data('geoLocations', () => ({
        districts: [],
        upazilas: [],
        division_id: '{{ old('division_id', $divisionId) }}',
        district_id: '{{ old('district_id', $districtId) }}',
        upazila_id: '{{ old('upazila_id', $upazilaId) }}',
        init() {
          if (this.division_id !== '') {
            this.getData('districts', DISTRICTS_API_URL + '?division_id=' + this.division_id)
          }
          if (this.district_id !== '') {
            this.getData('upazilas', UPAZILAS_API_URL + '?district_id=' + this.district_id)
          }
        },
        updateSelectValue(details) {
          this[details.key] = details.value;
          let url;
          let dataVarKey;
          if (details.key === 'division_id') {
            this.getData('districts', DISTRICTS_API_URL + '?division_id=' + this.division_id);
            this.district_id = "";
            this.upazila_id = "";
            this.upazilas = [];
          } else if (details.key === 'district_id') {
            this.getData('upazilas', UPAZILAS_API_URL + '?district_id=' + this.district_id);
            this.upazila_id = "";
          }
        },
        getData(key, url) {
          fetch(url, {
            headers: {
              'Accept': 'application/json',
            },
          })
            .then(response => response.json())
            .then(data => {
              this[key] = data.data;
              $('.select2').select2();
            })
            .catch((error) => {
              console.error('Error:', error);
            });
        }
      }))
    })

    $('#division_id, #district_id, #upazila_id').on('select2:select', function (e) {
      window.dispatchEvent(
        new CustomEvent("changed-select2", {
          detail: {
            key: e.target.getAttribute('x-model'),
            value: e.target.value
          }
        })
      );
    });
  </script>
@endpush
