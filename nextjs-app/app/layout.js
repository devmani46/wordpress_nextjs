import NavMenu from '@/components/NavMenu';
import { fetchMenu } from '@/lib/fetchMenu';

export default async function RootLayout({ children }) {
  // Fetch the primary menu
  let menuItems = [];
  try {
    menuItems = await fetchMenu('primary');
  } catch (error) {
    console.error('Failed to fetch menu:', error);
  }

  return (
    <html lang="en">
      <body>
        <header className="bg-white shadow-sm border-b">
          <div className="container mx-auto px-4 py-4">
            <div className="flex justify-between items-center">
              <Link href="/" className="text-xl font-bold">
                NRNA
              </Link>
              <NavMenu menuItems={menuItems} />
            </div>
          </div>
        </header>
        <main>{children}</main>
      </body>
    </html>
  );
}
