/**
 * Harsha Pest Control - Website Content & Configuration Data
 * 
 * Maintainer Note:
 * All business details, service lists, product catalog, pricing plans, and contact
 * information are structured here for clean, straightforward maintenance.
 * 
 * Items tagged `needsOwnerConfirmation: true` represent handwritten estimates or
 * unverified notes from reference papers that must be confirmed by Rakesh before official launch.
 */

const HARSHA_DATA = {
  business: {
    name: "Harsha Pest Control",
    contactPerson: "Rakesh",
    experienceYears: 20,
    experienceBadge: "20 Years of Pest Control Experience in Nacharam",
    phones: [
      {
        display: "63052 35360",
        raw: "+916305235360",
        isPrimary: true,
        label: "Primary Mobile / Calls"
      },
      {
        display: "88979 06391",
        raw: "+918897906391",
        isPrimary: false,
        label: "Alternative Contact"
      }
    ],
    // WhatsApp configuration - verify which phone number is active on WhatsApp
    whatsapp: {
      number: "+916305235360", // Defaulting to primary, marked for confirmation
      display: "63052 35360",
      needsConfirmation: true,
      defaultMessage: "Hello Rakesh garu, I am reaching out from Nacharam regarding pest control services / products for my property."
    },
    address: {
      street: "Snehapuri Colony, Behind HDFC Bank",
      area: "Nacharam",
      city: "Hyderabad",
      pincode: "500 076",
      state: "Telangana",
      full: "Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076"
    },
    serviceHours: {
      text: "Monday to Sunday (Call to confirm schedule & inspection slots)",
      needsConfirmation: true
    }
  },

  // 6 Pest Control Services as requested
  services: [
    {
      id: "cockroach",
      name: "Cockroach Control",
      teluguName: "బొద్దింకల నివారణ",
      shortDesc: "Targeted treatment for kitchen cabinets, sink gullies, pantry lofts, and pipe conduits where cockroaches breed.",
      treatmentDetails: "Gel baiting, crevice application, and drain point management targeting German and American cockroaches without offensive lingering fumes.",
      icon: "cockroach",
      scope: "Kitchen, Bathrooms & Pantries"
    },
    {
      id: "bedbug",
      name: "Bed Bug Control",
      teluguName: "నల్లుల నివారణ",
      shortDesc: "Meticulous spot treatment for cot joints, mattress beading, headboards, and wall skirting crevices.",
      treatmentDetails: "Systematic room inspection and targeted application focused on bed frames, seams, and dark corners where bugs conceal themselves.",
      icon: "bedbug",
      scope: "Bedrooms & Upholstered Furniture"
    },
    {
      id: "spider",
      name: "Spider Control",
      teluguName: "సాలీళ్ళ నివారణ",
      shortDesc: "Web clearance and corner barrier application for high ceilings, window grills, balconies, and utility lofts.",
      treatmentDetails: "Mechanical web removal along ceiling junctions and perimeter spot spray to deter new spider web construction.",
      icon: "spider",
      scope: "Ceilings, Grills & Balconies"
    },
    {
      id: "termite",
      name: "Termite Control",
      teluguName: "చెదలు & కబోర్డ్ వర్క్ నివారణ",
      shortDesc: "Anti-termite treatment for built-in cupboards, kitchen modular woodwork, wooden door frames, and wall joints.",
      treatmentDetails: "Precision wood drilling, chemical barrier injection, and localized surface treatments to protect interior woodwork from hollow damage.",
      icon: "termite",
      scope: "Cupboards, Doors & Woodwork"
    },
    {
      id: "mosquito",
      name: "Mosquito Management",
      teluguName: "దోమల నివారణ (గార్డెన్ & సెల్లార్)",
      shortDesc: "Perimeter residual spraying for compound gardens, apartment cellars, stilt parking, and shady outdoor zones.",
      treatmentDetails: "Targeted barrier spraying on foliage resting sites, boundary walls, and basement corners to curb adult mosquito resting areas.",
      icon: "mosquito",
      scope: "Outdoor Garden & Cellar Area"
    },
    {
      id: "rat",
      name: "Rat & Rodent Control",
      teluguName: "ఎలుకల నివారణ (గ్రౌండ్ ఫ్లోర్ & సెల్లార్)",
      shortDesc: "Safe baiting, trapping, and runway deterrence for apartment cellars, ground floors, gardens, and utility shafts.",
      treatmentDetails: "Secured tamper-evident bait stations and mechanical traps along active rodent runways to protect wiring, food stores, and cellars.",
      icon: "rat",
      scope: "Garden, Ground Floor & Cellar"
    }
  ],

  // Indicative Service Plans and Prices from supplied notes
  // Customer should confirm price and coverage with business before booking
  pricingPlans: [
    {
      id: "cockroach-plan",
      serviceName: "Cockroach Treatment",
      targetScope: "2 BHK Residential Flat",
      price: "₹1,500",
      rawPrice: 1500,
      warranty: "2-Year Warranty",
      warrantyNote: "2-year warranty as recorded in notes",
      freeService: "One Service Free",
      details: "Kitchen cabinets, drainage gullies, and crevice treatment. Notepad notes inclusion of red ants, black ants, and flies.",
      needsOwnerConfirmation: true,
      confirmationPoints: [
        "Confirm if ₹1,500 covers all standard 2 BHK layouts or varies by area/square footage.",
        "Confirm warranty terms and conditions for re-service.",
        "Confirm inclusion of ant and fly treatments."
      ]
    },
    {
      id: "termite-plan",
      serviceName: "Termite Treatment",
      targetScope: "2 BHK Woodwork & Cupboards",
      price: "₹4,500",
      rawPrice: 4500,
      warranty: "5-Year Warranty",
      warrantyNote: "5-year warranty as recorded in notes",
      freeService: "One Service Free",
      details: "Anti-termite treatment for kitchen cupboards, wardrobes, and wooden door frames using drill-and-inject barrier.",
      needsOwnerConfirmation: true,
      confirmationPoints: [
        "Confirm whether ₹4,500 covers entire 2 BHK woodwork or has linear footage limits.",
        "Confirm 5-year warranty certificate terms and inspection schedule."
      ]
    },
    {
      id: "mosquito-plan",
      serviceName: "Mosquito Treatment",
      targetScope: "Outdoor Garden & Cellar Area",
      price: "₹2,500",
      rawPrice: 2500,
      warranty: "6-Month Warranty",
      warrantyNote: "6-month warranty as recorded in notes",
      freeService: "One Service Free",
      details: "Outdoor garden perimeter and apartment cellar / stilt parking residual spraying to reduce mosquito resting sites.",
      needsOwnerConfirmation: true,
      confirmationPoints: [
        "Confirm garden and cellar square footage limits covered under ₹2,500.",
        "Confirm re-spray conditions under 6-month warranty."
      ]
    },
    {
      id: "rat-plan",
      serviceName: "Rat Treatment",
      targetScope: "Garden, Ground Floor, Apartment Cellar",
      price: "₹3,500",
      rawPrice: 3500,
      warranty: "2-Year Warranty",
      warrantyNote: "2-year warranty as recorded in notes",
      freeService: "One Service Free",
      details: "Garden, ground floor, and apartment cellar baiting and trapping along rodent entry pathways.",
      needsOwnerConfirmation: true,
      confirmationPoints: [
        "Verify bait replenishment schedule over 2-year warranty period.",
        "Confirm residential vs commercial property pricing."
      ]
    }
  ],

  // Dedicated Pest Control Products Catalog
  // Products, prices, and Call to Order actions
  // Easy-to-edit data structure without invented claims or misleading packaging
  products: [
    {
      id: "cockroach-gel",
      name: "Cockroach Gel",
      price: "₹550",
      rawPrice: 550,
      category: "Gel Bait Formulation",
      icon: "gel-tube",
      badge: "Targeted Kitchen Application",
      shortDesc: "Specialized gel bait for precision dot application inside kitchen cabinets, hinges, and drawer corners.",
      callActionNote: "Call Rakesh to confirm brand, tube size, and order for direct pickup or local delivery in Nacharam.",
      orderQuery: "Cockroach Gel (₹550)"
    },
    {
      id: "cockroach-spray",
      name: "Cockroach Spray Bottle",
      price: "₹1,200",
      rawPrice: 1200,
      category: "Ready-to-Use Liquid Spray",
      icon: "spray-bottle",
      badge: "Crevice & Drain Spray",
      shortDesc: "Spray bottle formulation designed for treating kitchen sink pipes, drainage gullies, and baseboard crevices.",
      callActionNote: "Call Rakesh to confirm bottle volume, brand, and place your order directly.",
      orderQuery: "Cockroach Spray Bottle (₹1,200)"
    },
    {
      id: "rat-medicine",
      name: "Rat Medicine",
      price: "₹250",
      rawPrice: 250,
      category: "Rodent Baiting Product",
      icon: "rodent-bait",
      badge: "Cellar & Garden Bait",
      shortDesc: "Rodent control bait formulation suitable for setting along compound walls, gardens, and apartment cellars.",
      callActionNote: "Call Rakesh to confirm formulation type, quantity, and safety precautions before ordering.",
      orderQuery: "Rat Medicine (₹250)"
    },
    {
      id: "snake-medicine",
      name: "Snake Medicine",
      price: "₹1,800",
      rawPrice: 1800,
      category: "Reptile Deterrent Formulation",
      icon: "snake-deterrent",
      badge: "Compound & Garden Barrier",
      shortDesc: "Specialized deterrent formulation suitable for application along compound walls, garden boundaries, and property perimeters.",
      callActionNote: "Call Rakesh to confirm formulation type, quantity, and safe perimeter placement guidelines before purchase.",
      orderQuery: "Snake Medicine (₹1,800)"
    },
    {
      id: "termite-spray",
      name: "Termite Spray Bottle",
      price: "₹2,000",
      rawPrice: 2000,
      category: "Woodwork Protection Spray",
      icon: "termite-canister",
      badge: "Cupboard & Door Frame Care",
      shortDesc: "Specialized spray bottle formulation for localized application on wooden cupboards, doors, and furniture joints.",
      callActionNote: "Call Rakesh to confirm container volume, dilution/application details, and order directly.",
      orderQuery: "Termite Spray Bottle (₹2,000)"
    },
    {
      id: "lizard-medicine",
      name: "Lizard Medicine (Liquid Deterrent)",
      price: "₹350",
      rawPrice: 350,
      category: "Liquid Pest Deterrent",
      icon: "lizard-deterrent",
      badge: "Wall & Window Frame Spray",
      shortDesc: "Specialized liquid deterrent spray formulation for walls, tube-light corners, window frames, and entry points to repel lizards.",
      callActionNote: "Call Rakesh to confirm bottle volume, formulation type, and order for direct pickup or local delivery in Nacharam.",
      orderQuery: "Lizard Medicine (Liquid Deterrent) (₹350)"
    }
  ],

  // About Us Content - Strictly based on 20 years of experience and factual local operation
  aboutUs: {
    experienceYears: 20,
    headline: "20 Years of Practical Pest Control Experience in Nacharam",
    summary: "Harsha Pest Control has been serving residential apartments, independent homes, and commercial properties in Nacharam and nearby Hyderabad areas for 20 years. Managed directly by Rakesh, we offer honest, straightforward pest management with personal accountability.",
    keyFacts: [
      {
        title: "20 Years of Local Experience",
        desc: "Two decades of hands-on familiarity with Nacharam's property layouts, termite vulnerability in woodwork, and seasonal pest patterns."
      },
      {
        title: "Direct Owner Accountability",
        desc: "You speak and coordinate directly with Rakesh. No call center intermediaries or anonymous sub-contractors."
      },
      {
        title: "Locally Based in Snehapuri Colony",
        desc: "Located behind HDFC Bank in Nacharam, ensuring reliable proximity for inspections and follow-up services."
      },
      {
        title: "Honest & Practical Advice",
        desc: "We focus on what is practically needed for your property rather than upselling unnecessary treatments or making unrealistic claims."
      }
    ]
  },

  // Practical 4-step workflow covering services & products
  howItWorks: [
    {
      step: "01",
      title: "Enquiry",
      desc: "Call or message Rakesh with details about your pest problem, property type (BHK or plot), or the specific pest product you require."
    },
    {
      step: "02",
      title: "Inspection or Quote",
      desc: "A physical site visit is arranged to inspect infestation severity and woodwork fittings, or an upfront quote is provided for products and treatments."
    },
    {
      step: "03",
      title: "Service / Product Confirmation",
      desc: "Once you confirm the service scope, pricing, and warranty terms (or product order), work is scheduled or products are prepared."
    },
    {
      step: "04",
      title: "Follow-up",
      desc: "For treatment packages with warranty, a follow-up check is conducted as per the agreed terms to evaluate effectiveness."
    }
  ],

  // Service area details & local SEO coverage structure
  serviceArea: {
    primaryBase: "Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076",
    pincode: "500 076",
    geoCoordinates: {
      latitude: 17.4325,
      longitude: 78.5583
    },
    coverageSummary: "Centrally based at Snehapuri Colony in Nacharam (500 076). Providing direct doorstep pest inspections, treatments, and product supplies across Nacharam, HMT Nagar, Babuji Nagar, Habsiguda, Tarnaka, and neighboring East Hyderabad localities.",
    coreLocalities: [
      {
        id: "nacharam",
        name: "Nacharam",
        pincode: "500 076",
        role: "Headquarters & Operating Base",
        desc: "Direct doorstep service for independent homes, apartment complexes, commercial establishments, and industrial premises across all sectors of Nacharam.",
        priorityPests: "Termite woodwork damage, German cockroaches in kitchen cabinets, rodent baiting in cellars."
      },
      {
        id: "snehapuri",
        name: "Snehapuri Colony",
        pincode: "500 076",
        role: "Local Base Behind HDFC Bank",
        desc: "Immediate local response for residents and businesses located behind HDFC Bank and adjacent residential lanes in Snehapuri Colony.",
        priorityPests: "Cockroach gel application, residential termite prevention, garden mosquito control."
      },
      {
        id: "hmt-babuji",
        name: "HMT Nagar & Babuji Nagar",
        pincode: "500 076",
        role: "Primary Residential Service Corridor",
        desc: "Routine visits for individual houses and multi-story apartments along HMT Nagar main road, Babuji Nagar lanes, and surrounding community sectors.",
        priorityPests: "Bed bug spot treatments, cockroach kitchen drain treatments, spider web clearance."
      },
      {
        id: "habsiguda",
        name: "Habsiguda",
        pincode: "500 076",
        role: "Immediate Adjacent Locality",
        desc: "Doorstep service along Street No. 1 to 8, Habsiguda crossroads, and residential colonies bordering Nacharam and Tarnaka.",
        priorityPests: "Anti-termite drill-and-injection for cupboards, flat cockroach eradication, rat traps."
      },
      {
        id: "tarnaka",
        name: "Tarnaka",
        pincode: "500 076 / 500 017",
        role: "Primary Service Hub",
        desc: "Serving university quarters, residential colonies, railway quarters vicinity, and main commercial junctions in Tarnaka.",
        priorityPests: "Cockroach gel baiting, mosquito perimeter spraying, termite barrier treatments."
      }
    ],
    extendedLocalities: [
      { name: "Mallapur", note: "Adjacent to Nacharam industrial and residential belt. Direct dispatch." },
      { name: "Boduppal", note: "Serving new residential apartments and independent villas. Scheduled visits." },
      { name: "Uppal", note: "Covering Uppal ring road, residential colonies, and commercial hubs. Call to schedule." },
      { name: "Mettuguda", note: "Connecting corridor between Tarnaka and Secunderabad. Scheduled visits." },
      { name: "Secunderabad", note: "Serving East Secunderabad localities. Pre-booked service slots." },
      { name: "Chengicherla", note: "Residential layouts and warehouse belts. Scheduled appointments." },
      { name: "Narapally", note: "Fast-developing residential sectors along Warangal highway. Pre-scheduled." },
      { name: "LB Nagar", note: "South-East Hyderabad sector. Scheduled visits upon call confirmation." }
    ],
    localPestInsights: [
      {
        title: "Modular Kitchens & Cockroaches",
        insight: "Warm voids behind built-in kitchen drawers and sink drain traps in 500076 apartments harbor German cockroaches. Our odorless gel baiting eliminates colonies without disturbing family cooking."
      },
      {
        title: "Woodwork & Anti-Termite Barriers",
        insight: "Plywood wardrobes and door frames in Nacharam, Habsiguda, and Boduppal face subterranean termite risks. We use targeted drill-and-inject barrier treatments backed by warranty."
      },
      {
        title: "Gardens, Stilt Parking & Cellars",
        insight: "Open compounds and shaded apartment cellars attract mosquitoes and rodents. We provide foliage perimeter spraying and secured tamper-evident rodent baiting."
      }
    ],
    transparencyNote: "Harsha Pest Control operates strictly from its physical address in Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076. We do not operate secondary physical branch offices. All inspections and service appointments are coordinated directly by Rakesh.",
    rankingDisclaimer: "Search rankings on Google Search and Google Maps are determined by Google algorithms based on searcher proximity, business relevance, and organic competition. We do not promise or claim guaranteed first-place rankings."
  },

  // Checklist of items needing owner sign-off prior to website public launch
  ownerVerificationChecklist: [
    {
      id: "chk-pricing",
      label: "Validate Service Prices",
      detail: "Confirm if ₹1,500 (Cockroach 2BHK), ₹4,500 (Termite 2BHK), ₹2,500 (Mosquito garden/cellar), and ₹3,500 (Rat cellar/garden) are standard flat rates or subject to layout variations."
    },
    {
      id: "chk-warranty",
      label: "Confirm Warranty & Free Service Terms",
      detail: "Confirm the documented warranty durations (Cockroach: 2 yrs; Termite: 5 yrs; Mosquito: 6 mos; Rat: 2 yrs) and the specific conditions governing the 'One Service Free'."
    },
    {
      id: "chk-products",
      label: "Confirm Product Availability & Sizes",
      detail: "Confirm current stock, container sizes (e.g. 50g gel, spray bottle volumes), and manufacturer details for Cockroach Gel (₹550), Cockroach Spray Bottle (₹1,200), Rat Medicine (₹250), Snake Medicine (₹1,800), and Termite Spray Bottle (₹2,000)."
    },
    {
      id: "chk-whatsapp",
      label: "Confirm Primary WhatsApp Number",
      detail: "Confirm whether 63052 35360 or 88979 06391 should be the designated WhatsApp contact for customer enquiries and orders."
    },
    {
      id: "chk-form-endpoint",
      label: "Connect Form Endpoint",
      detail: "Connect the online enquiry form to a real email/notification endpoint (e.g. Formspree/EmailJS) before launch, or route enquiries directly via phone/WhatsApp."
    },
    {
      id: "chk-area",
      label: "Service Area Coverage Radius",
      detail: "Confirm exact inspection coverage boundary around Nacharam and whether travel fees apply to distant Hyderabad neighborhoods."
    }
  ]
};

// Freeze object for data safety in browser runtime
if (typeof Object.freeze === "function") {
  Object.freeze(HARSHA_DATA);
}

// Global window attachment for browser & CommonJS export for Node test tooling
if (typeof window !== "undefined") {
  window.HARSHA_DATA = HARSHA_DATA;
}
if (typeof module !== "undefined" && module.exports) {
  module.exports = HARSHA_DATA;
}
