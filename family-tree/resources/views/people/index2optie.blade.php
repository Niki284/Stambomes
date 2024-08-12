@extends('layouts.base')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Family Tree</h1>
    <a href="{{ route('peoples.create') }}" class="btn btn-primary mb-3">Add User</a>
    <div id="tree" class="tree"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/users')
        .then(response => response.json())
        .then(users => {
            const root = document.getElementById('tree');
            const userMap = new Map();
            users.forEach(user => userMap.set(user.id, user));
            const tree = buildTree(users, userMap);
            root.appendChild(tree);
        });

    function buildTree(users, userMap) {
        const ul = document.createElement('ul');

        users.filter(user => !user.mother_id && !user.father_id).forEach(rootUser => {
            ul.appendChild(buildSubTree(rootUser, userMap));
        });

        return ul;
    }

    function createUserElement(user) {
        const container = document.createElement('div');
        container.className = 'user-container';

        const element = document.createElement('span');
        element.textContent = `${user.first_name} ${user.last_name}`;
        element.style.backgroundColor = user.gender === 'male' ? 'lightblue' : 'lightpink';

        if (user.spouse_id) {
            const spouseIcon = document.createElement('span');
            spouseIcon.className = 'spouse-icon';
            spouseIcon.textContent = '💍'; // Замените на подходящую иконку
            container.appendChild(spouseIcon);
        }

        container.appendChild(element);
        return container;
    }

    function buildSubTree(user, userMap) {
        const li = document.createElement('li');
        const span = createUserElement(user);
        li.appendChild(span);

        const ul = document.createElement('ul');

        // Add spouse
        if (user.spouse_id) {
            const spouse = userMap.get(user.spouse_id);
            if (spouse) {
                const spouseLi = document.createElement('li');
                spouseLi.appendChild(createUserElement(spouse));
                ul.appendChild(spouseLi);
            }
        }

        // Add children
        userMap.forEach(child => {
            if (child.father_id === user.id || child.mother_id === user.id) {
                ul.appendChild(buildSubTree(child, userMap));
            }
        });

        if (ul.childElementCount > 0) {
            li.appendChild(ul);
        }

        return li;
    }
});
</script>

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/users')
        .then(response => response.json())
        .then(users => {
            const root = document.getElementById('tree');
            const userMap = new Map();
            users.forEach(user => userMap.set(user.id, user));
            const tree = buildTree(users, userMap);
            root.appendChild(tree);
        });

    function buildTree(users, userMap) {
        const ul = document.createElement('ul');

        users.filter(user => !user.mother_id && !user.father_id).forEach(rootUser => {
            ul.appendChild(buildSubTree(rootUser, userMap));
        });

        return ul;
    }

    function createUserElement(user) {
        const element = document.createElement('span');
        element.textContent = `${user.first_name} ${user.last_name}`;
        element.style.backgroundColor = user.gender === 'male' ? 'lightblue' : 'lightpink';
        return element;
    }

    function buildSubTree(user, userMap) {
        const li = document.createElement('li');
        const span = createUserElement(user);
        li.appendChild(span);

        const ul = document.createElement('ul');

        // Add spouse
        if (user.spouse_id) {
            const spouse = userMap.get(user.spouse_id);
            if (spouse) {
                const spouseLi = document.createElement('li');
                spouseLi.appendChild(createUserElement(spouse));
                ul.appendChild(spouseLi);
            }
        }

        // Add children
        userMap.forEach(child => {
            if (child.father_id === user.id || child.mother_id === user.id) {
                ul.appendChild(buildSubTree(child, userMap));
            }
        });

        if (ul.childElementCount > 0) {
            li.appendChild(ul);
        }

        return li;
    }
});
</script> -->
@endsection
