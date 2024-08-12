@extends('layouts.base')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Family Tree</h1>
    <a href="{{ route('peoples.create') }}" class="btn btn-primary mb-3">Add User</a>
    <div id="d3-container" style="width: 100%; height: 600px;"></div>
</div>

<!-- D3.js CDN -->
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function loadGraph() {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => {
                const width = 960;
                const height = 600;

                const svg = d3.select("#d3-container").append("svg")
                    .attr("width", width)
                    .attr("height", height);

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = { id: user.id, name: `${user.first_name} ${user.last_name}`, gender: user.gender };
                    if (user.spouse_id) {
                        links.push({ source: user.id, target: user.spouse_id });
                    }
                    if (user.father_id) {
                        links.push({ source: user.father_id, target: user.id });
                    }
                    if (user.mother_id) {
                        links.push({ source: user.mother_id, target: user.id });
                    }
                });

                const simulation = d3.forceSimulation(Object.values(nodes))
                    .force("link", d3.forceLink(links).id(d => d.id))
                    .force("charge", d3.forceManyBody())
                    .force("center", d3.forceCenter(width / 2, height / 2));

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link");

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    })
                    .on("contextmenu", function(event, d) {
                        event.preventDefault();
                        showContextMenu(event, d.id, d.name);
                    });

                node.append("circle")
                    .attr("r", 10)
                    .attr("fill", d => d.gender === 'male' ? 'blue' : 'pink');

                node.append("text")
                    .attr("dx", 12)
                    .attr("dy", ".35em")
                    .text(d => d.name);

                simulation.nodes(Object.values(nodes)).on("tick", ticked);
                simulation.force("link").links(links);

                function ticked() {
                    link
                        .attr("x1", d => d.source.x)
                        .attr("y1", d => d.source.y)
                        .attr("x2", d => d.target.x)
                        .attr("y2", d => d.target.y);

                    node
                        .attr("transform", d => `translate(${d.x},${d.y})`);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    }

    function showContextMenu(event, nodeId, userName) {
        const contextMenu = document.createElement('div');
        contextMenu.style.position = 'absolute';
        contextMenu.style.top = `${event.clientY}px`;
        contextMenu.style.left = `${event.clientX}px`;
        contextMenu.style.background = 'white';
        contextMenu.style.border = '1px solid #ccc';
        contextMenu.style.padding = '10px';
        contextMenu.style.zIndex = 1000;

        const updateButton = document.createElement('button');
        updateButton.innerText = 'Update';
        updateButton.classList.add('btn', 'btn-primary', 'btn-sm');
        updateButton.onclick = function () {
            window.location.href = `/peoples/${nodeId}/edit`;
        };

        const deleteButton = document.createElement('button');
        deleteButton.innerText = 'Delete';
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ml-2');
        deleteButton.onclick = function () {
            if (confirm(`Are you sure you want to delete ${userName}?`)) {
                fetch(`/peoples/${nodeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        loadGraph();
                    } else {
                        alert('Failed to delete user.');
                    }
                })
                .catch(error => console.error('Error deleting user:', error));
            }
        };

        contextMenu.appendChild(updateButton);
        contextMenu.appendChild(deleteButton);

        document.body.appendChild(contextMenu);

        document.addEventListener('click', function onDocumentClick() {
            contextMenu.remove();
            document.removeEventListener('click', onDocumentClick);
        }, { once: true });
    }

    loadGraph();
});
</script>
@endsection


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree with D3.js</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        .node {
            cursor: pointer;
        }
        .node circle {
            stroke: #fff;
            stroke-width: 1.5px;
        }
        .node text {
            font: 12px sans-serif;
        }
        .link {
            fill: none;
            stroke: #aaa;
            stroke-width: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Family Tree</h1>
    <a href="{{ route('peoples.create') }}" class="btn btn-primary mb-3">Add User</a>
    <div id="tree" class="tree"></div>
</div>  
    <div id="d3-container"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => {
                const width = 960;
                const height = 600;
                
                const svg = d3.select("#d3-container").append("svg")
                    .attr("width", width)
                    .attr("height", height);

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = { id: user.id, name: `${user.first_name} ${user.last_name}`, gender: user.gender, date:` ${user.birth_date} - ${user.death_date}` };
                    if (user.spouse_id) {
                        links.push({ source: user.id, target: user.spouse_id });
                    }
                    if (user.father_id) {
                        links.push({ source: user.father_id, target: user.id });
                    }
                    if (user.mother_id) {
                        links.push({ source: user.mother_id, target: user.id });
                    }
                });

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id))
                    .force("charge", d3.forceManyBody())
                    .force("center", d3.forceCenter(width / 2, height / 2));

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link");

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node");

                node.append("circle")
                    .attr("r", 10)
                    .attr("fill", d => d.gender === 'male' ? 'blue' : 'pink');

                node.append("text")
                    .attr("dx", 16)
                    .attr("dy", 6)
                    .text(d => d.name);

                force.nodes(Object.values(nodes)).on("tick", ticked);
                force.force("link").links(links);

                function ticked() {
                    link
                        .attr("x1", d => d.source.x)
                        .attr("y1", d => d.source.y)
                        .attr("x2", d => d.target.x)
                        .attr("y2", d => d.target.y);

                    node
                        .attr("transform", d => `translate(${d.x},${d.y})`);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    });
    </script>
</body>
</html>
