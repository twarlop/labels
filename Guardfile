# This will concatenate the javascript files specified in :files to public/js/all.js
guard :concat, type: "js", files: %w(init queue options confirmation CategoryInspect suggestProduct suggestCategory), input_dir: "js/src", output: "js/etiketten"

guard 'less',
  :all_on_start => true,
  :all_after_change => true,
  :compress => true do
  watch(%r{^.+\.less$})
end


guard 'livereload' do
  watch(%r{css/etiketten\.css})
  watch(%r{js/etiketten\.js})
end


# guard 'uglify', :destination_file => "public/javascripts/application.js" do
#   watch (%r{app/assets/javascripts/application.js})
# end
