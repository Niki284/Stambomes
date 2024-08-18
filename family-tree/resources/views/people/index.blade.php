@php
    $currentUserId = $currentUserId;
@endphp

@extends('layouts.app')

@section('content')

<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold text-gray-800">Family Tree</h1>
        <div class="flex space-x-4">
            <a href="{{ route('peoples.index') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg shadow hover:from-orange-600 hover:to-orange-700 transition-transform transform hover:scale-105">Tree</a>
            <a href="{{ route('peoples.create') }}" class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-4 py-2 rounded-lg shadow hover:from-cyan-600 hover:to-cyan-700 transition-transform transform hover:scale-105">Add User</a>
            <a href="{{ route('peoples.search') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg shadow hover:from-gray-600 hover:to-gray-700 transition-transform transform hover:scale-105">Search Peoples</a>

            @if(Auth::user()->is_admin)
                <a href="/admin" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg shadow hover:from-green-600 hover:to-green-700 transition-transform transform hover:scale-105">Admin Panel</a>
            @endif 
        </div>
    </div>

    <div id="tree" class="tree"></div>
</div>

<div id="d3-container" class="mt-10 p-5 bg-white shadow-lg rounded-lg border border-gray-200"></div>

<script src="https://d3js.org/d3.v7.min.js"></script>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://d3js.org/d3.v7.min.js"></script>


<script src="https://d3js.org/d3.v7.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = {{ $currentUserId ?? 'null' }};

    if (currentUserId !== null) {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => {
                const width = 1200;
                const height = 800;

                const svg = d3.select("#d3-container").append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .call(d3.zoom().on("zoom", function(event) {
                        svg.attr("transform", event.transform);
                    }))
                    .append("g");

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = {
                        id: user.id,
                        name: `${user.first_name} ${user.last_name}`,
                        gender: user.gender,
                        dates: `${user.birth_date} - ${user.death_date || ''}`,
                        //avatar: user.avatar,
                        avatar: user.avatar ? `${user.avatar}` : '/storage/public/user.jpg',

                        death_date: user.death_date, // Добавляем дату смерти в узел
                        weight: 0,
                        children_ids: []
                    };

                    if (user.spouse_id && nodes[user.spouse_id]) {
                        links.push({
                            source: user.id,
                            target: user.spouse_id,
                            type: 'spouse'
                        });
                    }

                    if (user.father_id) {
                        links.push({
                            source: user.father_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.father_id]) {
                            nodes[user.father_id].children_ids.push(user.id);
                        }
                    }

                    if (user.mother_id) {
                        links.push({
                            source: user.mother_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.mother_id]) {
                            nodes[user.mother_id].children_ids.push(user.id);
                        }
                    }
                });

                function assignWeights(user, nodes) {
                    nodes[user.id].weight = 0;

                    function setWeight(node, currentWeight) {
                        node.weight = currentWeight;

                        if (node.father_id && nodes[node.father_id]) {
                            setWeight(nodes[node.father_id], currentWeight + 1);
                        }
                        if (node.mother_id && nodes[node.mother_id]) {
                            setWeight(nodes[node.mother_id], currentWeight + 1);
                        }
                        if (node.spouse_id && nodes[node.spouse_id]) {
                            setWeight(nodes[node.spouse_id], currentWeight);
                        }
                        if (node.children_ids && node.children_ids.length > 0) {
                            node.children_ids.forEach(child_id => {
                                setWeight(nodes[child_id], currentWeight - 1);
                            });
                        }
                    }

                    setWeight(nodes[user.id], 0);
                }

                const user1 = data.find(u => u.id === currentUserId);
                assignWeights(user1, nodes);

                const height_of_middle = height / 2;

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id).distance(250))
                    .force("charge", d3.forceManyBody().strength(-350))
                    .force("center", d3.forceCenter(width / 2, height / 2))
                    .force("y", d3.forceY(d => height_of_middle + d.weight * -300))
                    .force("spouse", () => {
                        links.forEach(link => {
                            if (link.type === 'spouse') {
                                const yPos = (link.source.y + link.target.y) / 2;
                                link.source.y = yPos;
                                link.target.y = yPos;
                            }
                        });
                    });

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link")
                    .style("stroke", d => d.type === 'spouse' ? "#888" : "#555")
                    .style("stroke-width", 2);

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("mouseover", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 35)
                            .attr("stroke-width", 4);
                    })
                    .on("mouseout", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 30)
                            .attr("stroke-width", 2);
                    })
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    });

                node.append("circle")
                    .attr("r", 30)
                    .attr("fill", d => d.gender === 'male' ? '#4A90E2' : '#E94E77')
                    .attr("stroke", "white")
                    .attr("stroke-width", 2)
                    .attr("class", "cursor-pointer");

                node.append("image")
                    .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                    .attr("x", -20)
                    .attr("y", -20)
                    .attr("height", 40)
                    .attr("width", 40)
                    .attr("clip-path", "circle(20px)");

                node.append("text")
                    .attr("dy", 50)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-sm font-medium")
                    .text(d => d.name)
                    .style("fill", "#333");

                node.append("text")
                    .attr("dy", 65)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-xs text-gray-600")
                    .text(d => d.dates);

                // Добавление иконки при наличии даты смерти
                node.append("text")
                    .attr("dy", -35)
                    .attr("dx", 25)
                    .attr("font-family", "FontAwesome")
                    .attr("font-size", "16px")
                    .attr("fill", "#ff0000")
                    .text(d => d.death_date ? "deatch" : ""); // Иконка предупреждения из FontAwesome

                const spouseIcon = svg.append("g")
                    .attr("class", "spouse-icons")
                    .selectAll("text")
                    .data(links.filter(d => d.type === 'spouse'))
                    .enter().append("text")
                    .attr("dy", -4)
                    .attr("dx", 4)
                    .attr("font-family", "FontAwesome")
                    .attr("font-size", "16px")
                    .attr("fill", "#E94E77")
                    .text("\uf004"); // Иконка сердечка из FontAwesome

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

                    spouseIcon
                        .attr("x", d => (d.source.x + d.target.x) / 2)
                        .attr("y", d => (d.source.y + d.target.y) / 2);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    } else {
        console.error('No user found with the given beheerder_id');
    }
});

</script>



@endsection


<!-- <script>
    
document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = {{ $currentUserId ?? 'null' }};

    if (currentUserId !== null) {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => {
                const width = 1200;
                const height = 800;

                const svg = d3.select("#d3-container").append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .call(d3.zoom().on("zoom", function(event) {
                        svg.attr("transform", event.transform);
                    }))
                    .append("g");

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = {
                        id: user.id,
                        name: `${user.first_name} ${user.last_name}`,
                        gender: user.gender,
                        dates: `${user.birth_date} - ${user.death_date || ''}`,
                        avatar: user.avatar,
                        weight: 0,
                        children_ids: []
                    };

                    // Добавляем связь между супругами
                    if (user.spouse_id && nodes[user.spouse_id]) {
                        links.push({
                            source: user.id,
                            target: user.spouse_id,
                            type: 'spouse'
                        });
                    }

                    // Добавляем связь между отцом и ребенком
                    if (user.father_id) {
                        links.push({
                            source: user.father_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.father_id]) {
                            nodes[user.father_id].children_ids.push(user.id);
                        }
                    }

                    // Добавляем связь между матерью и ребенком
                    if (user.mother_id) {
                        links.push({
                            source: user.mother_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.mother_id]) {
                            nodes[user.mother_id].children_ids.push(user.id);
                        }
                    }
                });

                function assignWeights(user, nodes) {
                    nodes[user.id].weight = 0;

                    function setWeight(node, currentWeight) {
                        node.weight = currentWeight;

                        if (node.father_id && nodes[node.father_id]) {
                            setWeight(nodes[node.father_id], currentWeight + 1);
                        }
                        if (node.mother_id && nodes[node.mother_id]) {
                            setWeight(nodes[node.mother_id], currentWeight + 1);
                        }
                        if (node.spouse_id && nodes[node.spouse_id]) {
                            setWeight(nodes[node.spouse_id], currentWeight);
                        }
                        if (node.children_ids && node.children_ids.length > 0) {
                            node.children_ids.forEach(child_id => {
                                setWeight(nodes[child_id], currentWeight - 1);
                            });
                        }
                    }

                    setWeight(nodes[user.id], 0);
                }

                const user1 = data.find(u => u.id === currentUserId);
                assignWeights(user1, nodes);

                const height_of_middle = height / 2;

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id).distance(250))
                    .force("charge", d3.forceManyBody().strength(-350))
                    .force("center", d3.forceCenter(width / 2, height / 2))
                    .force("y", d3.forceY(d => height_of_middle + d.weight * -300))
                    .force("spouse", () => {
                        links.forEach(link => {
                            if (link.type === 'spouse') {
                                const yPos = (link.source.y + link.target.y) / 2;
                                link.source.y = yPos;
                                link.target.y = yPos;
                            }
                        });
                    });

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link")
                    .style("stroke", d => d.type === 'spouse' ? "#888" : "#555")
                    .style("stroke-width", 2);

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("mouseover", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 35)
                            .attr("stroke-width", 4);
                    })
                    .on("mouseout", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 30)
                            .attr("stroke-width", 2);
                    })
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    });

                node.append("circle")
                    .attr("r", 30)
                    .attr("fill", d => d.gender === 'male' ? '#4A90E2' : '#E94E77')
                    .attr("stroke", "white")
                    .attr("stroke-width", 2)
                    .attr("class", "cursor-pointer");

                node.append("image")
                    .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                    .attr("x", -20)
                    .attr("y", -20)
                    .attr("height", 40)
                    .attr("width", 40)
                    .attr("clip-path", "circle(20px)");

                node.append("text")
                    .attr("dy", 50)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-sm font-medium")
                    .text(d => d.name)
                    .style("fill", "#333");

                node.append("text")
                    .attr("dy", 65)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-xs text-gray-600")
                    .text(d => d.dates);

                const spouseIcon = svg.append("g")
                    .attr("class", "spouse-icons")
                    .selectAll("text")
                    .data(links.filter(d => d.type === 'spouse'))
                    .enter().append("text")
                    .attr("dy", -4)
                    .attr("dx", 4)
                    .attr("font-family", "FontAwesome")
                    .attr("font-size", "16px")
                    .attr("fill", "#E94E77")
                    .text("\uf004"); // Иконка сердечка из FontAwesome

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

                    spouseIcon
                        .attr("x", d => (d.source.x + d.target.x) / 2)
                        .attr("y", d => (d.source.y + d.target.y) / 2);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    } else {
        console.error('No user found with the given beheerder_id');
    }
});
</script> -->

<!-- copi code voor reserf -->
<!-- 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = {{ $currentUserId ?? 'null' }};

    if (currentUserId !== null) {
        fetch('/api/users')
            .then(response => response.json())
            .then(data => {
                const width = 1200;
                const height = 800;

                const svg = d3.select("#d3-container").append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .call(d3.zoom().on("zoom", function(event) {
                        svg.attr("transform", event.transform);
                    }))
                    .append("g");

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = {
                        id: user.id,
                        name: `${user.first_name} ${user.last_name}`,
                        gender: user.gender,
                        dates: `${user.birth_date} - ${user.death_date || ''}`,
                        avatar: user.avatar,
                        weight: 0,
                        children_ids: []
                    };

                    // Добавляем связь между супругами
                    if (user.spouse_id && nodes[user.spouse_id]) {
                        links.push({
                            source: user.id,
                            target: user.spouse_id,
                            type: 'spouse'
                        });
                    }

                    // Добавляем связь между отцом и ребенком
                    if (user.father_id) {
                        links.push({
                            source: user.father_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.father_id]) {
                            nodes[user.father_id].children_ids.push(user.id);
                        }
                    }

                    // Добавляем связь между матерью и ребенком
                    if (user.mother_id) {
                        links.push({
                            source: user.mother_id,
                            target: user.id,
                            type: 'parent'
                        });
                        if (nodes[user.mother_id]) {
                            nodes[user.mother_id].children_ids.push(user.id);
                        }
                    }
                });

                function assignWeights(user, nodes) {
                    nodes[user.id].weight = 0;

                    function setWeight(node, currentWeight) {
                        node.weight = currentWeight;

                        if (node.father_id && nodes[node.father_id]) {
                            setWeight(nodes[node.father_id], currentWeight + 1);
                        }
                        if (node.mother_id && nodes[node.mother_id]) {
                            setWeight(nodes[node.mother_id], currentWeight + 1);
                        }
                        if (node.spouse_id && nodes[node.spouse_id]) {
                            setWeight(nodes[node.spouse_id], currentWeight);
                        }
                        if (node.children_ids && node.children_ids.length > 0) {
                            node.children_ids.forEach(child_id => {
                                setWeight(nodes[child_id], currentWeight - 1);
                            });
                        }
                    }

                    setWeight(nodes[user.id], 0);
                }

                const user1 = data.find(u => u.id === currentUserId);
                assignWeights(user1, nodes);

                const height_of_middle = height / 2;

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id).distance(250))
                    .force("charge", d3.forceManyBody().strength(-350))
                    .force("center", d3.forceCenter(width / 2, height / 2))
                    .force("y", d3.forceY(d => height_of_middle + d.weight * -300));

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link")
                    .style("stroke", d => d.type === 'spouse' ? "#888" : "#555")
                    .style("stroke-width", 2);

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("mouseover", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 35)
                            .attr("stroke-width", 4);
                    })
                    .on("mouseout", function(event, d) {
                        d3.select(this).select("circle").transition().duration(300)
                            .attr("r", 30)
                            .attr("stroke-width", 2);
                    })
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    });

                node.append("circle")
                    .attr("r", 30)
                    .attr("fill", d => d.gender === 'male' ? '#4A90E2' : '#E94E77')
                    .attr("stroke", "white")
                    .attr("stroke-width", 2)
                    .attr("class", "cursor-pointer");

                node.append("image")
                    .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                    .attr("x", -20)
                    .attr("y", -20)
                    .attr("height", 40)
                    .attr("width", 40)
                    .attr("clip-path", "circle(20px)");

                node.append("text")
                    .attr("dy", 50)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-sm font-medium")
                    .text(d => d.name)
                    .style("fill", "#333");

                node.append("text")
                    .attr("dy", 65)
                    .attr("text-anchor", "middle")
                    .attr("class", "text-xs text-gray-600")
                    .text(d => d.dates);

                // Добавляем иконку сердечка между супругами
                const spouseIcon = svg.append("g")
                    .attr("class", "spouse-icons")
                    .selectAll("text")
                    .data(links.filter(d => d.type === 'spouse'))
                    .enter().append("text")
                    .attr("dy", -6)
                    .attr("dx", 6)
                    .attr("font-family", "FontAwesome")
                    .attr("font-size", "12px")
                    .attr("fill", "#E94E77")
                    .text("\uf004"); // Иконка сердечка из FontAwesome

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

                    spouseIcon
                        .attr("x", d => (d.source.x + d.target.x) / 2)
                        .attr("y", d => (d.source.y + d.target.y) / 2);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    } else {
        console.error('No user found with the given beheerder_id');
    }
});
</script> -->

