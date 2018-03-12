window.onload(
  (function() {
    var c;
    var payload;
    var last_id;
    var module_id;
    var element_id;
    var meta_data;
    var module_name;
    var module_table;
    var table_entries;
    var rows = [];

    $(window).load(function() {
      /* Handles Service and table name build when view data is click from the Service Panel */
      if (
        $("#service option:selected").val() != "" &&
        $("#table_name option:selected").val() != ""
      ) {
        table_entries =
          $("#service option:selected").text() +
          "_" +
          $("#table_name option:selected").text();
        module_name = $("#service option:selected").text();
        module_id = $("#service").val();
        $.get("/datatable/" + module_id, function(data) {
          meta_data = data;
        });
        tableCall(table_entries);
      }
    });

    var entries;
    var Datatable;
    var metaForm;

    // Initiate table build
    function tableCall(table_entries) {
      var metas;

      $.get("/datatable/" + table_entries + "/metas", function(res) {
        metas = res;
        if (metas !== undefined) {
          $.get("/datatable/" + table_entries + "/entries", function(resp) {
            $("#addbtn").prop("disabled", false);
            navOption(resp, metas);
          });
        }
      });
    }

    // Ajax to retrieve table names and append it to the DOM on module name change
    $("#service").change(function() {
      module_id = $("#service").val();
      module_name = $("#service option:selected").text();

      $("#table_name")
        .find("option")
        .remove()
        .end()
        .append(
          "<option disabled selected value> -- select a table -- </option>"
        );
      $.get("/datatable/" + module_id, function(data) {
        meta_data = data;
        var tables = data;
        for (var i = 0; i < tables.length; i++) {
          $("#table_name").append(
            $("<option>")
              .val(JSON.parse(tables[i].id))
              .text(JSON.parse(tables[i].schema).name)
          );
        }
      });
    });

    // setting module table name when viewing from module edit
    if (window.location.search !== "") {
      module_table = $("#table_name option:selected").text();
    }

    // Handles removal of table from the DOM on table option change
    $("#table_name").change(function(data) {
      module_table = $("#table_name option:selected").text();
      table_entries = module_name + "_" + module_table;
      $("#dataOne").remove();
      $("#dataOne_wrapper").remove();
      tableCall(table_entries);
    });

    window.refreshTable = function() {
      if ($("#service option:selected").text() !== " -- select a service -- ") {
        var service_name = $("#service option:selected").text();
        var table_name = $("#table_name option:selected").text();
        var devless_table_name = service_name + "_" + table_name;
        $("#dataOne").remove();
        $("#dataOne_wrapper").remove();
        return tableCall(table_entries);
      }
      window.location = window.location;
    };

    // Handle table creation with row & columns
    function buildHtmlTable(data, metaData) {
      var table =
        '<div class="table-responsive"><table id="dataOne" cellspacing="0" width="100%" class="display compact cell-border no-wrap"><thead id="table_head"></thead><tbody id="table_body"></tbody></table></div>';
      $(".panel").append(table);
      var columns = addAllColumnHeaders(metaData);

      for (i = 0; i < data.length; i++) {
        table_bd = '<tr id="dtRow">';
        for (j = 0; j < columns.length; j++) {
          table_bd += "<td>" + data[i][columns[j]] + "</td>";
        }
        table_bd += "</tr>";
        $("#table_body").append(table_bd);
      }
      $(".loader").remove();
      Datatable = $("#dataOne").DataTable({
        dom: "Bfrtip",
        responsive: true,
        scrollX: true,
        buttons: [
          "excelHtml5",
          "csvHtml5",
          {
            text: "JSON",
            action: function(e, dt, button, config) {
              console.log(dt.header());
              var heads = [];

              $("thead")
                .find("th")
                .each(function() {
                  heads.push(
                    $(this)
                      .text()
                      .trim()
                  );
                });

              var data = Datatable.rows().data();

              data.map((v, i) => {
                var cur = {};
                v.map((val, idx) => {
                  cur[heads[idx]] = val;
                });
                rows.push(cur);
                cur = {};
              });

              $.fn.dataTable.fileSave(
                new Blob([JSON.stringify(rows)]),
                `${module_name}_${module_table}.json`
              );
            }
          }
        ]
      });
    }

    // Creation of table headers
    function addAllColumnHeaders(metas) {
      var table_head = "<tr>";
      var header = [];

      if (metas === undefined) {
        alert("Please refresh your page. Xhr failed");
      }

      for (i = 0; i < metas.length; i++) {
        if (metas[i] !== "devless_user_id") {
          header.push(metas[i]);
          table_head += "<th>" + metas[i] + "</th>";
        }
      }

      table_head += "</tr>";
      $("#table_head").append(table_head);

      return header;
    }

    // Building of table
    function navOption(data, metas) {
      var entries = data;

      // Reverse the header array
      var splicedArr = metas.splice(2).reverse();
      metas = metas.concat(splicedArr);
      metaForm = metas;

      // Begin building table
      buildHtmlTable(entries, metas);
    }

    // Code snippet for converting form data into an object (key & value)
    function jQExtn() {
      $.fn.serializeObject = function() {
        var obj = {};
        var arr = this.serializeArray();
        $.each(arr, function() {
          if (obj[this.name] !== undefined) {
            if (!obj[this.name].push) {
              obj[this.name] = [obj[this.name]];
            }
            obj[this.name].push(this.value || "");
          } else {
            obj[this.name] = this.value || "";
          }
        });
        return obj;
      };
    }

    // Handles the form update with data when a row is clicked
    $(document).on("click", "#dtRow", function() {
      // grab row id
      element_id = $(this).find("tr").context._DT_RowIndex;

      c = $(this)
        .find("td")
        .map(function() {
          return $(this).html();
        })
        .get();

      $(function modal() {
        fieldType();
        $("#formData").html(" ");

        // Get title of headers into array to identity ref type on update
        var thArray = [];
        var field_names = [];

        var nRow = $("table thead tr")[0];
        $.each(nRow.cells, function(i, v) {
          thArray.push(v.innerText);
        });

        for (var i = 0; i < metaForm.length; i++) {
          if (metaForm[i].field_type === "reference") {
            field_names.push({
              name: metaForm[i].name,
              ref: metaForm[i].ref_table
            });

            formBuild("formData", "select", metaForm[i]);
          } else {
            formBuild("formData", "input", metaForm[i]);
            $("#" + metaForm[i].name).val(c[i + 1]);
          }
        }
        getOptions(field_names, module_name, thArray);
      });
      $("#flash_msg").modal({ show: true, backdrop: "static" });
      jQExtn();
    });

    // Handle submission of data to the backend
    $(function() {
      var $form = $("form");
      var submitActor = null;
      var $submitActors = $form.find("button[type=submit]");

      $form.submit(function(e) {
        e.preventDefault();
        payload = $(this).serializeObject();

        var table_array = [0];

        // Grabs values from the payload (form data) and push them into an array for DataTable library
        $.map(payload, function(v, i) {
          table_array.push(v);
        });

        switch (submitActor.name) {
          case "Cancel":
            alertHandle();
            break;
          case "Submit":
            SDK.addData(module_name, module_table, payload, function(res) {
              alertHandle();
              if (res.status_code === 609) {
                // ID received from the backend
                table_array[0] = res.payload.entry_id;

                Datatable.row.add(table_array).draw();
                row_index = Datatable.row([Datatable.data().length - 1]);
                new_row = $("#dataOne")
                  .DataTable()
                  .row(row_index)
                  .node();
                $(new_row).attr("id", "dtRow");
              } else {
                $("#error_flash").modal("show");
                $("pre").text(JSON.stringify(data, null, 4));
                // $("#error_display").text(JSON.stringify(data));
              }
            });
            break;
          case "Update":
            info = {
              resource: [
                {
                  name: module_table,
                  params: [{ where: "id," + c[0], data: [payload] }]
                }
              ]
            };

            // Grab id from the row since it doesn't need to be changed during update
            update_array = [Datatable.row(element_id).data()[0]];
            // Push data into array for the row to be updated
            $.map(payload, function(v, i) {
              update_array.push(v);
            });

            $.ajax({
              url: "api/v1/service/" + module_name + "/db",
              type: "PATCH",
              data: info
            }).done(function(data) {
              alertHandle();
              if (data.status_code === 619) {
                Datatable.row(element_id).data(update_array);
              } else {
                $("#error_flash").modal("show");
                $("pre").text(JSON.stringify(data, null, 4));
                // $("#error_display").text(JSON.stringify(data));
              }
            });
            break;
          case "Delete":
            SDK.deleteData(module_name, module_table, "id", c[0], function(
              data
            ) {
              alertHandle();
              if (data.status_code === 636) {
                Datatable.row(element_id)
                  .remove()
                  .draw();
              } else {
                $("#error_flash").modal("show");
                $("pre").text(JSON.stringify(data, null, 4));
                // $("#error_display").text(JSON.stringify(data));
              }
            });
            break;
        }

        return false;
      });

      $submitActors.click(function(event) {
        submitActor = this;
      });
    });

    // Handles form creation when the add btn is clicked
    $("#addbtn").click(function() {
      var field_names = [];
      fieldType();
      $("#addform").html(" ");
      for (i = 0; i < metaForm.length; i++) {
        if (metaForm[i].field_type === "reference") {
          field_names.push({
            name: metaForm[i].name,
            ref: metaForm[i].ref_table
          });
          formBuild("addform", "select", metaForm[i]);
        } else {
          formBuild("addform", "input", metaForm[i]);
        }
      }

      getOptions(field_names, module_name);

      $("#add_form").modal({ show: true, backdrop: "static" });
      jQExtn();
    });

    // Append label and input types to modal
    function formBuild(identifier, type, meta) {
      var option;
      var label = $("<label>")
        .attr("for", meta.name)
        .css("font-weight", "bold")
        .text(meta.name);
      if (type === "input") {
        option = $('<input type="text">')
          .attr({
            id: meta.name,
            name: meta.name
          })
          .addClass("form-control");
      } else {
        option = $("<select>")
          .attr({
            id: meta.name,
            name: meta.name
          })
          .addClass("form-control");
      }
      $("#" + identifier).append([label, option]);
    }

    // Retrieve ref options and append to select field
    function getOptions(field_names, module_name, thArray) {
      $.each(field_names, function(i, v) {
        if (v.ref !== "_devless_users") {
          SDK.queryData(module_name, v.ref, {}, function(res) {
            appendOptions(v, res.payload.results, thArray);
          });
        } else {
          SDK.call("devless", "getAllUsers", [], function(res) {
            appendOptions(v, res.payload.result, thArray, "users");
          });
        }
      });
    }

    // Handle callback to add option inputs
    function appendOptions(field, payload, thArray, options = null) {
      payload.forEach(function(element) {
        if (options !== "users") {
          $("#" + field.name).append(
            $("<option>")
              .val(element.id)
              .text(
                element[Object.keys(element)[Object.keys(element).length - 1]]
              )
          );
        } else {
          $("#" + field.name).append(
            $("<option>")
              .val(element.id)
              .text(element.id + " - " + element.email)
          );
        }
      }, this);
      if (thArray !== undefined) {
        thArray.map(function(value, index) {
          if (field.name === value) {
            $("#" + field.name).val(c[index]);
          }
        });
      }
    }

    // Hides form modal
    function alertHandle() {
      $("#formData").html(" ");
      $("#add_form").modal("hide");
      $("#flash_msg").modal("hide");
    }

    // Check for field type
    function fieldType() {
      meta_data.forEach(function(element) {
        if (element.table_name === table_entries) {
          metaForm = JSON.parse(element.schema).field.reverse();
        }
      }, this);
    }
  })()
);
