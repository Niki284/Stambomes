@extends('layouts.app')

@section('content')

<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between	">
        <h1 class="mb-6 text-4xl font-bold text-gray-800">Family Tree</h1>
        <div class="flex justify-center mb-6">
            <a href="{{ route('peoples.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg shadow hover:bg-orange-700 transition-transform transform hover:scale-105 mx-2">Tree</a>
            <a href="{{ route('peoples.create') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-lg shadow hover:bg-cyan-700 transition-transform transform hover:scale-105 mx-2">Add User</a>
            <a href="{{ route('peoples.search') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition-transform transform hover:scale-105 mx-2">Search Peoples</a>
        </div>
    </div>

    <div id="tree" class="tree"></div>
</div>

<div id="d3-container"></div>

<!-- <script>
   document.addEventListener('DOMContentLoaded', function() {
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

            svg.append("defs").append("marker")
                .attr("id", "arrow")
                .attr("viewBox", "0 -5 10 10")
                .attr("refX", 25)
                .attr("refY", 0)
                .attr("markerWidth", 6)
                .attr("markerHeight", 6)
                .attr("orient", "auto")
                .append("path")
                .attr("d", "M0,-5L10,0L0,5")
                .attr("class", "arrowHead");

            const links = [];
            const nodes = {};

            // Создание узлов и связей
            data.forEach(user => {
                nodes[user.id] = {
                    id: user.id,
                    name: `${user.first_name} ${user.last_name}`,
                    gender: user.gender,
                    dates: `${user.birth_date} - ${user.death_date || ''}`,
                    avatar: user.avatar,
                    weight: 0,  // начальное значение веса
                    children_ids: []
                };

                if (user.spouse_id && nodes[user.spouse_id]) {
                    links.push({
                        source: user.id,
                        target: user.spouse_id
                    });
                }
                if (user.father_id) {
                    links.push({
                        source: user.father_id,
                        target: user.id
                    });
                    if (nodes[user.father_id]) {
                        nodes[user.father_id].children_ids.push(user.id);
                    }
                }
                if (user.mother_id) {
                    links.push({
                        source: user.mother_id,
                        target: user.id
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

            const userher = data.find(u => u.id === 98); // Замените на реальный ID пользователя
            assignWeights(userher, nodes);

            const height_of_middle = height / 2;

            const force = d3.forceSimulation()
                .force("link", d3.forceLink().id(d => d.id).distance(250))
                .force("charge", d3.forceManyBody().strength(-350))
                .force("center", d3.forceCenter(width / 2, height / 2))
                .force("y", d3.forceY(d => height_of_middle + d.weight * -300)); // Уровни распределяются по весам

            const link = svg.append("g")
                .attr("class", "links")
                .selectAll("line")
                .data(links)
                .enter().append("line")
                .attr("class", "link")
                .attr("marker-end", "url(#arrow)");

            const node = svg.append("g")
                .attr("class", "nodes")
                .selectAll("g")
                .data(Object.values(nodes))
                .enter().append("g")
                .attr("class", "node")
                .on("click", function(event, d) {
                    window.location.href = `/peoples/${d.id}`;
                });

            node.append("circle")
                .attr("r", 25)
                .attr("fill", d => d.gender === 'male' ? 'blue' : 'pink');

            node.append("image")
                .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                .attr("x", -20)
                .attr("y", -20)
                .attr("height", 40)
                .attr("width", 40);

            node.append("text")
                .attr("dy", 35)
                .text(d => d.name);

            node.append("text")
                .attr("dy", 50)
                .text(d => d.dates);

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
</script> -->


<!-- tweede code  -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/users') // Замените путь на маршрут для метода getUsers
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

                // Добавляем маркеры стрелок
                svg.append("defs").append("marker")
                    .attr("id", "arrow")
                    .attr("viewBox", "0 -5 10 10")
                    .attr("refX", 25)
                    .attr("refY", 0)
                    .attr("markerWidth", 6)
                    .attr("markerHeight", 6)
                    .attr("orient", "auto")
                    .append("path")
                    .attr("d", "M0,-5L10,0L0,5")
                    .attr("class", "arrowHead");

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = {
                        id: user.id,
                        name: `${user.first_name} ${user.last_name}`,
                        gender: user.gender,
                        dates: `${user.birth_date} - ${user.death_date || ''}`,
                        avatar: user.avatar,
                        type: user.paternal_grandfather_id === user.id ? 'paternal-grandfather' :
                            user.paternal_grandmother_id === user.id ? 'paternal-grandmother' :
                            user.maternal_grandfather_id === user.id ? 'maternal-grandfather' :
                            user.maternal_grandmother_id === user.id ? 'maternal-grandmother' :
                            'normal'
                    };

                    // Добавляем связи для отца и матери, если они существуют
                    if (user.spouse_id && nodes[user.spouse_id]) {
                        links.push({
                            source: user.id,
                            target: user.spouse_id
                        });
                    }
                    if (user.father_id) {
                        links.push({
                            source: user.father_id,
                            target: user.id
                        });

                        // Добавляем узлы и связи для родителей отца (дедушек и бабушек по папиной линии)
                        if (user.paternal_grandfather_id) {
                            nodes[user.paternal_grandfather_id] = nodes[user.paternal_grandfather_id] || {
                                id: user.paternal_grandfather_id,
                                name: `Paternal Grandfather`,
                                type: 'paternal-grandfather'
                            };
                            links.push({
                                source: user.paternal_grandfather_id,
                                target: user.father_id
                            });
                        }
                        if (user.paternal_grandmother_id) {
                            nodes[user.paternal_grandmother_id] = nodes[user.paternal_grandmother_id] || {
                                id: user.paternal_grandmother_id,
                                name: `Paternal Grandmother`,
                                type: 'paternal-grandmother'
                            };
                            links.push({
                                source: user.paternal_grandmother_id,
                                target: user.father_id
                            });
                        }
                    }
                    if (user.mother_id) {
                        links.push({
                            source: user.mother_id,
                            target: user.id
                        });

                        // Добавляем узлы и связи для родителей матери (дедушек и бабушек по маминой линии)
                        if (user.maternal_grandfather_id) {
                            nodes[user.maternal_grandfather_id] = nodes[user.maternal_grandfather_id] || {
                                id: user.maternal_grandfather_id,
                                name: `Maternal Grandfather`,
                                type: 'maternal-grandfather'
                            };
                            links.push({
                                source: user.maternal_grandfather_id,
                                target: user.mother_id
                            });
                        }
                        if (user.maternal_grandmother_id) {
                            nodes[user.maternal_grandmother_id] = nodes[user.maternal_grandmother_id] || {
                                id: user.maternal_grandmother_id,
                                name: `Maternal Grandmother`,
                                type: 'maternal-grandmother'
                            };
                            links.push({
                                source: user.maternal_grandmother_id,
                                target: user.mother_id
                            });
                        }
                    }
                });

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id).distance(250)) // Увеличено расстояние между узлами
                    .force("charge", d3.forceManyBody().strength(-350)) // Увеличено отталкивание узлов
                    .force("center", d3.forceCenter(width / 2, height / 2));
                   


                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link")
                    .attr("marker-end", "url(#arrow)");

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    });

                node.append("circle")
                    .attr("r", 25)
                    .attr("fill", d => d.gender === 'male' ? 'blue' : 'pink')
                    .attr("class", d => d.type);

                node.append("image")
                    .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                    .attr("x", -20)
                    .attr("y", -20)
                    .attr("height", 40)
                    .attr("width", 40)
                    .attr("class", "avatar");

                node.append("text")
                    .attr("dy", 35)
                    .text(d => d.name);

                node.append("text")
                    .attr("dy", 50)
                    .text(d => d.dates);

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
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/users') // Замените путь на маршрут для метода getUsers
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

                // Добавляем маркеры стрелок
                svg.append("defs").append("marker")
                    .attr("id", "arrow")
                    .attr("viewBox", "0 -5 10 10")
                    .attr("refX", 25)
                    .attr("refY", 0)
                    .attr("markerWidth", 6)
                    .attr("markerHeight", 6)
                    .attr("orient", "auto")
                    .append("path")
                    .attr("d", "M0,-5L10,0L0,5")
                    .attr("class", "arrowHead");

                const links = [];
                const nodes = {};

                data.forEach(user => {
                    nodes[user.id] = {
                        id: user.id,
                        name: `${user.first_name} ${user.last_name}`,
                        gender: user.gender,
                        dates: `${user.birth_date} - ${user.death_date || ''}`,
                        avatar: user.avatar,
                        father_id: user.father_id || null,
                        mother_id: user.mother_id || null,
                        spouse_id: user.spouse_id || null,
                        children_ids: []
                        // type: user.paternal_grandfather_id === user.id ? 'paternal-grandfather' :
                        //     user.paternal_grandmother_id === user.id ? 'paternal-grandmother' :
                        //     user.maternal_grandfather_id === user.id ? 'maternal-grandfather' :
                        //     user.maternal_grandmother_id === user.id ? 'maternal-grandmother' :
                        //     'normal'
                    };


                    //Add links for spouse, father, and mother if they exist
                    // if (user.spouse_id && nodes[user.spouse_id]) {
                    //     links.push({
                    //         source: user.id,
                    //         target: user.spouse_id
                    //     });
                    // }
                    if (user.father_id) {
                        links.push({
                            source: user.father_id,
                            target: user.id
                        });
                        // Add this user to the father's children list
                        if (nodes[user.father_id]) {
                            nodes[user.father_id].children_ids.push(user.id);
                        }
                    }
                    if (user.mother_id) {
                        links.push({
                            source: user.mother_id,
                            target: user.id
                        });
                        // Add this user to the mother's children list
                        if (nodes[user.mother_id]) {
                            nodes[user.mother_id].children_ids.push(user.id);
                        }
                    }
                });

                function assignWeights(user, nodes) {
                    // Initialize the weight of the starting user node
                    nodes[user.id].weight = 0;

                    // Recursive function to assign weights
                    function setWeight(node, currentWeight) {
                        // Set the weight for the current node
                        node.weight = currentWeight;

                        // Assign weight to the father and mother
                        if (node.father_id && nodes[node.father_id]) {
                            if (nodes[node.father_id].weight === undefined) {
                                setWeight(nodes[node.father_id], currentWeight + 1);
                            }
                        }
                        if (node.mother_id && nodes[node.mother_id]) {
                            if (nodes[node.mother_id].weight === undefined) {
                                setWeight(nodes[node.mother_id], currentWeight + 1);
                            }
                        }

                        // Assign weight to the spouse
                        if (node.spouse_id && nodes[node.spouse_id]) {
                            if (nodes[node.spouse_id].weight === undefined) {
                                setWeight(nodes[node.spouse_id], currentWeight);
                            }
                        }

                        // Assign weight to the children
                        if (node.children_ids && node.children_ids.length > 0) {
                            node.children_ids.forEach(child_id => {
                                if (nodes[child_id].weight === undefined) {
                                    setWeight(nodes[child_id], currentWeight - 1);
                                }
                            });
                        }
                    }

                    // Start the recursive weight assignment from the initial user node
                    setWeight(nodes[user.id], 0);
                }

                // Example usage
                const userher = data.find(u => u.id === 98); // Replace 'some_user_id' with the actual user ID
                assignWeights(userher, nodes);

                // Example usage
                const height_of_middle = height / 2; // Calculate the middle height of the screen

                const force = d3.forceSimulation()
                    .force("link", d3.forceLink().id(d => d.id).strength(0)) // Увеличено расстояние между узлами
                    
                    //.force("link", d3.forceLink().id(d => d.id).distance(250)) // Увеличено расстояние между узлами

                    .force("charge", d3.forceManyBody().strength(-350)) // Увеличено отталкивание узлов
                     .force("center", d3.forceCenter(width / 2, height / 2))
                    .force("y", d3.forceY(d => height_of_middle + d.weight * -300)); // Position vertically based on weight
                    // .force("link", d3.forceLink(links).id(d => d.id).distance(250))
                    // .force("charge", d3.forceManyBody().strength(-350))
                    // .force("center", d3.forceCenter(width / 2, height_of_middle))
                    // .force("x", d3.forceX(width / 2)) // Center horizontally
                    // .force("y", d3.forceY(d => height_of_middle + d.weight * 100)); // Position vertically based on weight

                const link = svg.append("g")
                    .attr("class", "links")
                    .selectAll("line")
                    .data(links)
                    .enter().append("line")
                    .attr("class", "link")
                    .attr("marker-end", "url(#arrow)");

                const node = svg.append("g")
                    .attr("class", "nodes")
                    .selectAll("g")
                    .data(Object.values(nodes))
                    .enter().append("g")
                    .attr("class", "node")
                    .on("click", function(event, d) {
                        window.location.href = `/peoples/${d.id}`;
                    });

                node.append("circle")
                    .attr("r", 25)
                    .attr("fill", d => d.gender === 'male' ? 'blue' : 'pink')
                    .attr("class", d => d.type);

                node.append("image")
                    .attr("xlink:href", d => d.avatar ? `storage/${d.avatar}` : 'default-avatar.png')
                    .attr("x", -20)
                    .attr("y", -20)
                    .attr("height", 40)
                    .attr("width", 40)
                    .attr("class", "avatar");

                node.append("text")
                    .attr("dy", 35)
                    .text(d => d.name);

                node.append("text")
                    .attr("dy", 50)
                    .text(d => d.dates);

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
</script>  -->


@endsection