$(document).ready(function () {
    const userName = "Jimmie"; 
    const dummyData = [
      {
        name: "Acacia Silverman",
        distance: "1 Km Away",
        image: "./src/images/listitem1.png",
      },
      {
        name: "Ben Oakfield",
        distance: "2 Km Away",
        image: "./src/images/listitem2.png",
      },
      {
        name: "Chloe Pinewood",
        distance: "3 Km Away",
        image: "./src/images/listitem3.png",
      },
      {
        name: "Acacia Silverman",
        distance: "1 Km Away",
        image: "./src/images/listitem1.png",
      },
      {
        name: "Ben Oakfield",
        distance: "2 Km Away",
        image: "./src/images/listitem2.png",
      },
      {
        name: "Chloe Pinewood",
        distance: "3 Km Away",
        image: "./src/images/listitem3.png",
      },
      {
        name: "Acacia Silverman",
        distance: "1 Km Away",
        image: "./src/images/listitem1.png",
      },
      {
        name: "Ben Oakfield",
        distance: "2 Km Away",
        image: "./src/images/listitem2.png",
      },
      {
        name: "Chloe Pinewood",
        distance: "3 Km Away",
        image: "./src/images/listitem3.png",
      },
      {
        name: "Acacia Silverman",
        distance: "1 Km Away",
        image: "./src/images/listitem1.png",
      },
      {
        name: "Ben Oakfield",
        distance: "2 Km Away",
        image: "./src/images/listitem2.png",
      },
      {
        name: "Chloe Pinewood",
        distance: "3 Km Away",
        image: "./src/images/listitem3.png",
      },
      {
        name: "Acacia Silverman",
        distance: "1 Km Away",
        image: "./src/images/listitem1.png",
      },
      {
        name: "Ben Oakfield",
        distance: "2 Km Away",
        image: "./src/images/listitem2.png",
      },
      {
        name: "Chloe Pinewood",
        distance: "3 Km Away",
        image: "./src/images/listitem3.png",
      },
    ];
  
    function renderList(data) {
      const $nearbyList = $("#nearby-list");
      $nearbyList.empty();
      data.forEach((item) => {
        const listItem = `
          <li>
            <div class="item_rec">
              <div class="profilemain">
                <div class="pre_img">
                  <img src="${item.image}" alt="">
                </div>
                <div class="content">
                  <h3>${item.name}</h3>
                  <p>
                    <span>
                      <img src="./src/images/locationicon.svg" alt="">
                    </span>
                    ${item.distance}
                  </p>
                </div>
              </div>
              <div class="buttonslist">
                <a href="#" class="btn btn-outline-primary">See profile</a>
                <a href="#" class="btn btn-secondary">Let's chat</a>
              </div>
            </div>
          </li>`;
        $nearbyList.append(listItem);
      });
    }
  
    $("#welcome-text").text(`Welcome ${userName}`);
    renderList(dummyData);
  
    $("#search-box").on("input", function () {
      const query = $(this).val().toLowerCase();
      const filteredResults = dummyData.filter((item) =>
        item.name.toLowerCase().includes(query)
      );
      renderList(filteredResults);
      if (query) {
        $("#welcome-text").text("Search Families");
        $("#recommendation-text").hide();
        $("#clear-btn").show();
        $(".searching").addClass("searchactive");
      } else {
        $("#welcome-text").text(`Welcome ${userName}`);
        $("#recommendation-text").show();
        $("#clear-btn").hide();
        $(".searching").removeClass("searchactive");
      }
    });
  
    $("#clear-btn").on("click", function () {
      $("#search-box").val("");
      $("#clear-btn").hide();
      $(".searching").removeClass("searchactive");
      $("#welcome-text").text(`Welcome ${userName}`);
      $("#recommendation-text").show();
      renderList(dummyData); 
    });
  });
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
  