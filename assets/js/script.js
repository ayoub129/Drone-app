// TO MAKE THE MAP APPEAR YOU MUST
// ADD YOUR ACCESS TOKEN FROM
// https://account.mapbox.com
mapboxgl.accessToken = 'pk.eyJ1IjoiYXlvdWJiciIsImEiOiJjbGY5eGwxdWcyNHByM3ZvMTF6djNub2NmIn0.vc_PN2f4yV4U4mUrOguqsw';

let zoom = 8;
let Lon = -5.8340;
let Lat = 35.7595;

const map = new mapboxgl.Map({
container: 'map',
// Choose from Mapbox's core styles, or make your own style with Mapbox Studio
style: 'mapbox://styles/mapbox/streets-v12',
center: [Lon , Lat],
zoom: zoom
});

// responsive
const burger = document.getElementById('burger')

// get the ip address
function ipLookUp () {
    fetch('http://ip-api.com/json')
    .then((response) => response.json())
    .then((data) => {
        const marker3 = new mapboxgl.Marker({ color: 'black'})
        .setLngLat([data.lon, data.lat])
        .addTo(map);
    });
}

ipLookUp()
  

// add markers for the trash
const lat = document.querySelectorAll('.lat')
const lon = document.querySelectorAll('.lon')

lon.forEach((lontitude , index) => {
    const adminuser = document.getElementById('adminuser').value
    
    let color ;
    if (adminuser == 'admin') {
        color = 'red'
    } else {
        color = 'rgb(234 ,179 ,8)'
    }
    const marker2 = new mapboxgl.Marker({ color: color})
    .setLngLat([lontitude.value, lat[index].value])
    .addTo(map);
})


const nameclick = document.querySelectorAll('.nameclick')
const clos = document.querySelectorAll('.close')
const modal = document.querySelector('.custom-model-main')

nameclick.forEach(namec => {
    namec.addEventListener('click' , (e) => {
        modal.classList.add('model-open')
        
        const trashId = parseInt(e.target.parentElement.children[4].value)
        const popupUsers = document.querySelectorAll('.popup-users')
        popupUsers.forEach(user => {
            user.addEventListener('click' , (e) => {
                const userId = parseInt(e.target.parentElement.children[1].value)
                
                // update trash status and user id 
                async function updateData(url = "", data = {}) {
                    const response = await fetch(url, {
                      method: "UPDATE", 
                      mode: "cors", 
                      cache: "no-cache", 
                      credentials: "same-origin", 
                      headers: {
                        "Content-Type": "application/json",
                      },
                      redirect: "follow", 
                      referrerPolicy: "no-referrer",
                      body: JSON.stringify(data), 
                    });
                    return response.json(); // parses JSON response into native JavaScript objects
                }
              
                  updateData("update-status.php", {trashId:trashId , userId:userId , userStatus:'working' }).then((data) => { 
                      // done notification and refresh 
                      window.location.reload();
                  });


            })
        })
    })
})

clos.forEach(cl => {
    cl.addEventListener('click' , () => {
        modal.classList.remove('model-open')
    })
})