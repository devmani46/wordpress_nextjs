'use client';

import { useState } from 'react';
import Link from 'next/link';

export default function NavMenu({ menuItems }) {
  const [openDropdowns, setOpenDropdowns] = useState({});

  const toggleDropdown = (id) => {
    setOpenDropdowns(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  const renderMenuItem = (item, depth = 0) => {
    const hasChildren = item.children && item.children.length > 0;
    const isOpen = openDropdowns[item.id];

    return (
      <li key={item.id} className="relative">
        {hasChildren ? (
          <div className="relative">
            <button
              onClick={() => toggleDropdown(item.id)}
              className={`flex items-center px-3 py-2 text-sm font-medium hover:bg-gray-100 rounded-md transition-colors ${
                depth > 0 ? 'w-full text-left' : ''
              }`}
              aria-expanded={isOpen}
            >
              {item.title}
              <svg
                className={`ml-1 h-4 w-4 transition-transform ${isOpen ? 'rotate-180' : ''}`}
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            {isOpen && (
              <ul className={`${
                depth === 0
                  ? 'absolute left-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50'
                  : 'mt-1 ml-4 space-y-1'
              }`}>
                {item.children.map(child => renderMenuItem(child, depth + 1))}
              </ul>
            )}
          </div>
        ) : (
          <Link
            href={item.url}
            target={item.target || '_self'}
            className={`block px-3 py-2 text-sm font-medium hover:bg-gray-100 rounded-md transition-colors ${
              depth > 0 ? 'w-full' : ''
            }`}
          >
            {item.title}
          </Link>
        )}
      </li>
    );
  };

  return (
    <nav className="hidden md:block">
      <ul className="flex space-x-1">
        {menuItems.map(item => renderMenuItem(item))}
      </ul>
    </nav>
  );
}
