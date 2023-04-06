@extends("platform::dashboard")

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('wa-users/send/campaign') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="validity">language</label>
                            
                            <select name="language" class="form-control" id="lan">

                                <option value="en_US">English</option>
                                <option value="sq">Albanian</option>
                            </select>
                        </div>
                       
                        <div class="form-group">
                            <label for="validity">Validity Date</label>
                            <input type="text" class="form-control" id="validity" name="validity_date" placeholder="Validity Date">
                        </div>
                        <div class="form-group">
                            <label for="validity">Number of Products</label>
                            <input type="number" class="form-control" id="validity" name="number_of_products" placeholder="Number of Products">
                        </div>
                        <div class="form-group">
                            <label for="description">Products</label>
                            <textarea wrap="hard" class="form-control" id="description" name="products" rows="6" placeholder="Enter Products with their links"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Campaign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
