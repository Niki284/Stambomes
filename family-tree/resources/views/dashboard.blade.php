<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg">
                <div class="p-10 text-gray-900">

                    <!-- Раздел "Families" -->
                    <div class="text-center sm:text-left mb-10">
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-6">Families</h1>
                        <div class="flex flex-col sm:flex-row items-center space-y-6 sm:space-y-0 sm:space-x-8">
                            <img src="/famillie.jpg" alt="Family" class="h-60 w-60 rounded-2xl shadow-lg object-cover border-4 border-gray-300">
                            <p class="text-xl text-gray-700">
                                The following map shows the language families of Europe (distinguished by color) and languages within those families. Note that the terms “language” and “dialect” are not mutually exclusive, and some of the languages shown in the map may be considered dialects of others.
                            </p>
                        </div>
                        <div class="mt-8 flex justify-center sm:justify-start">
                            <a class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition duration-300" href="{{ route('peoples.index') }}">Create Your Tree</a>

                            @if(Auth::user()->is_admin)
                                <a class="bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition duration-300 ml-4" href="/admin">Admin Panel</a>
                            @endif
                        </div>
                    </div>

                    <!-- Раздел "Introduction Video" -->
                    <div class="mt-16">
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-8 text-center">Introduction Video</h1>
                        <video class="w-full max-w-6xl rounded-2xl shadow-xl border-4 border-gray-300" controls>
                            <source src="demo.mkv" type="video/mp4">
                            <source src="movie.ogg" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Раздел "World Map" с кнопкой поиска -->
                    <div id="map-container" class="mt-16 mb-16">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                            <div class="text-center md:text-left">
                                <h1 class="text-5xl font-extrabold text-gray-800 mb-4">World Map</h1>
                                <p class="text-lg text-gray-700">
                                    Explore the world map to search for your family members by country, name, or family name.
                                </p>
                            </div>
                            <!-- Кнопка поиска -->
                            <a href="{{ route('peoples.search') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition duration-300 mt-6 md:mt-0">
                                Search User
                            </a>
                        </div>
                        <svg id="world-map" class="w-full h-96 rounded-xl shadow-xl border-4 border-gray-300"></svg>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Подключение D3.js и рендеринг карты -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
        // Размеры SVG
        const width = 960;
        const height = 500;

        // Создаем проекцию карты
        const projection = d3.geoNaturalEarth1()
            .scale(160)
            .translate([width / 2, height / 2]);

        const path = d3.geoPath().projection(projection);

        // Создаем SVG-контейнер с возможностью масштабирования и перемещения
        const svg = d3.select("#world-map")
            .attr("width", width)
            .attr("height", height)
            .call(d3.zoom().on("zoom", (event) => {
                svg.attr("transform", event.transform);
            }))
            .append("g");

        // Загрузка и отображение карты мира
        d3.json("https://raw.githubusercontent.com/holtzy/D3-graph-gallery/master/DATA/world.geojson").then(function(data) {
            svg.append("g")
                .selectAll("path")
                .data(data.features)
                .enter()
                .append("path")
                .attr("d", path)
                .attr("fill", "#69b3a2")
                .attr("stroke", "#fff")
                .attr("stroke-width", 0.5);

            // Дополнительный интерактив: при наведении изменить цвет
            svg.selectAll("path")
                .on("mouseover", function(event, d) {
                    d3.select(this).attr("fill", "orange");
                })
                .on("mouseout", function(event, d) {
                    d3.select(this).attr("fill", "#69b3a2");
                });
        });
    </script>
</x-app-layout>
