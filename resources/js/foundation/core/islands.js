const registry = new Map();

function parseProps(element) {
    const rawProps = element.dataset.props;

    if (!rawProps) {
        return {};
    }

    try {
        return JSON.parse(rawProps);
    } catch {
        return {};
    }
}

export function registerIsland(name, mount) {
    if (!name || typeof mount !== 'function') {
        return;
    }

    registry.set(name, mount);
}

export function mountIslands(root = document) {
    root.querySelectorAll('[data-foundation-island]').forEach((element) => {
        const name = element.dataset.foundationIsland;
        const mount = registry.get(name);

        if (!mount || element.dataset.foundationIslandMounted === 'true') {
            return;
        }

        mount(element, parseProps(element));
        element.dataset.foundationIslandMounted = 'true';
    });
}

window.FoundationIslands = {
    register: registerIsland,
    mountAll: mountIslands,
};
