<h2>ERIC</h2>
<div class="col-xs-12 col-md-6 text-left">
  {{ link_to("eric/new", "Crear", "class": "btn btn-primary") }}
</div>
<table class="table table-bordered table-striped" align="center">
  <thead>
    <tr>
      <th>Id</th>
      <th>Description</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    {% for eric in erics %}
      <tr>
        <td>{{eric.id}}</td>
        <td>{{eric.description}}</td>
        <td>{{eric.price}}</td>
        <td>
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-md-6 text-left">
                {{ link_to(url('eric/delete/'~eric.id), "Delete", "class": "btn btn-danger") }}
              </div>
              <div class="col-xs-12 col-md-6 text-left">
                {{ link_to(url('eric/edit/'~eric.id), "Edit", "class": "btn btn-info") }}
              </div>
            </div>
          </div>
          
          
        </td>
      </tr>
    {% endfor %}
  </tbody>
</table>
