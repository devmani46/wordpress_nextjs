/**
 * Fetch menu items from WordPress REST API
 * @param {string} location - The menu location (e.g., 'primary')
 * @returns {Promise<Array>} - Array of menu items in tree structure
 */
export async function fetchMenu(location = 'primary') {
  const baseUrl = process.env.NEXT_PUBLIC_WORDPRESS_URL || 'http://localhost:3000';
  const response = await fetch(`${baseUrl}/wp-json/nrna/v1/menu/${location}`);

  if (!response.ok) {
    throw new Error(`Failed to fetch menu: ${response.statusText}`);
  }

  const menuItems = await response.json();
  return menuItems;
}
