# Because swagger requires a lot of arguments, codify them in this script.
# Generates the testSpec into php models only.
swagger-codegen generate -o gen -i testSpec.yml -l php -c swagger-config.json \
  -Dmodels,api,modelDocs=false,modelTests=false,apiDocs=false,apiTests=false

# -DsupportingFiles is needed as a one off to generate some files?